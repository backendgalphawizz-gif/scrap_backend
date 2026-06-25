<?php

namespace App\Services;

use App\CPU\Helpers;
use App\Models\Campaign;
use App\Models\CampaignTransaction;

class CaptionVerificationService
{
    private const INSTAGRAM_USERNAME = 'rexarix_official';
    private const FACEBOOK_USERNAME = 'rexarixhq';
    private const THREADS_USERNAME = 'rexarix_official';

    public const MISMATCH_REASON = 'Caption mismatch: your post caption does not match the required caption. Please use the exact caption provided without adding or removing any words or lines.';

    public function buildExpectedCaption(Campaign $campaign, CampaignTransaction $transaction): string
    {
        $platform = $transaction->shared_on;
        $content = (string) ($campaign->descriptions ?? '');
        $tags = $this->formatCampaignTags((string) ($campaign->tags ?? ''));
        if ($tags !== '') {
            $content .= "\n" . $tags;
        }

        $footer = (string) (Helpers::get_business_settings('post_footer_content') ?? '');
        if ($footer === '') {
            $footer = 'Follow us on @' . self::INSTAGRAM_USERNAME;
        }

        if ($platform === 'facebook') {
            $footer = str_replace('@' . self::INSTAGRAM_USERNAME, '@' . self::FACEBOOK_USERNAME, $footer);
        } elseif ($platform === 'threads') {
            $footer = str_replace('@' . self::INSTAGRAM_USERNAME, '@' . self::THREADS_USERNAME, $footer);
        }

        $brand = $campaign->relationLoaded('brand') ? $campaign->brand : $campaign->brand()->first();
        $brandHandle = match ($platform) {
            'instagram' => $brand?->instagram_username,
            'facebook'  => $brand?->facebook_username,
            'threads'   => $brand?->threads_username,
            default     => null,
        };

        $message = $content;
        if (!empty($brandHandle)) {
            $message .= "\n\n@" . ltrim((string) $brandHandle, '@');
        }

        if ($platform === 'facebook') {
            $message .= "\n\n\n" . $footer . "\n\n[" . $transaction->unique_code . ']';
        } else {
            $message .= "\n\n" . $footer . "\n\n[" . $transaction->unique_code . ']';
        }

        return $message;
    }

    public function buildScrapedCaption(?object $row): ?string
    {
        if (!$row || empty($row->caption)) {
            return null;
        }

        $parts = [trim((string) $row->caption)];

        foreach (['hashtags', 'mentions'] as $field) {
            $value = trim((string) ($row->$field ?? ''));
            if ($value !== '') {
                $parts[] = $this->dedupeSeparatedTokens(str_replace(',', ' ', $value));
            }
        }

        $merged = trim(implode(' ', $parts));

        $uniqueCode = trim((string) ($row->unique_code ?? ''));
        if ($uniqueCode !== '' && stripos($merged, $uniqueCode) === false) {
            $merged .= ' [' . $uniqueCode . ']';
        }

        return $merged;
    }

    public function hasMismatch(string $expected, string $actual): bool
    {
        return $this->extractWordBag($expected) !== $this->extractWordBag($actual);
    }

    /** @return array<string, int> */
    private function extractWordBag(string $caption): array
    {
        $bag = [];

        foreach ($this->extractCaptionStructure($caption) as $lineWords) {
            foreach ($lineWords as $word) {
                $bag[$word] = ($bag[$word] ?? 0) + 1;
            }
        }

        ksort($bag);

        return $bag;
    }

    /** @return list<list<string>> */
    public function extractCaptionStructure(string $caption): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($caption)) ?: [];
        $structure = [];

        foreach ($lines as $line) {
            $words = $this->extractWords($line);
            if ($words !== []) {
                $structure[] = $words;
            }
        }

        return $structure;
    }

    /** @return list<string> */
    private function extractWords(string $line): array
    {
        $tokens = preg_split('/\s+/u', trim($line), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $words = [];

        foreach ($tokens as $token) {
            $word = mb_strtolower((string) preg_replace('/[^\p{L}\p{N}\-]/u', '', $token));
            if ($word !== '') {
                $words[] = $word;
            }
        }

        return $words;
    }

    private function formatCampaignTags(string $rawTags): string
    {
        if ($rawTags === '') {
            return '';
        }

        $parts = preg_split('/[\s,]+/', trim($rawTags), -1, PREG_SPLIT_NO_EMPTY);
        if (!$parts) {
            return '';
        }

        return implode(' ', array_map(function (string $tag) {
            return str_starts_with($tag, '#') ? $tag : '#' . $tag;
        }, $parts));
    }

    private function dedupeSeparatedTokens(string $raw): string
    {
        $parts = preg_split('/[\s,]+/', trim($raw), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $seen  = [];
        $out   = [];

        foreach ($parts as $part) {
            $key = mb_strtolower((string) preg_replace('/[^\p{L}\p{N}\-]/u', '', $part));
            if ($key !== '' && !isset($seen[$key])) {
                $seen[$key] = true;
                $out[]       = $part;
            }
        }

        return implode(' ', $out);
    }
}
