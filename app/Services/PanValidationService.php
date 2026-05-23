<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Seller;
use App\Models\User;

class PanValidationService
{
    public function normalizePan(string $panNumber): string
    {
        return strtoupper(trim($panNumber));
    }

    public function isPanTaken(
        string $panNumber,
        ?int $excludeUserId = null,
        ?int $excludeSellerId = null,
        ?int $excludeSaleId = null
    ): bool {
        $pan = $this->normalizePan($panNumber);
        if ($pan === '') {
            return false;
        }

        $userQuery = User::query()
            ->whereRaw('UPPER(TRIM(pan_number)) = ?', [$pan])
            ->whereNotNull('pan_number')
            ->where('pan_number', '!=', '');
        if ($excludeUserId) {
            $userQuery->where('id', '!=', $excludeUserId);
        }
        if ($userQuery->exists()) {
            return true;
        }

        $sellerQuery = Seller::query()
            ->whereRaw('UPPER(TRIM(pan_number)) = ?', [$pan])
            ->whereNotNull('pan_number')
            ->where('pan_number', '!=', '');
        if ($excludeSellerId) {
            $sellerQuery->where('id', '!=', $excludeSellerId);
        }
        if ($sellerQuery->exists()) {
            return true;
        }

        $saleQuery = Sale::query()
            ->whereRaw('UPPER(TRIM(pan_number)) = ?', [$pan])
            ->whereNotNull('pan_number')
            ->where('pan_number', '!=', '');
        if ($excludeSaleId) {
            $saleQuery->where('id', '!=', $excludeSaleId);
        }

        return $saleQuery->exists();
    }

    public function namesMatch(string $profileName, ?string $panRegisteredName): bool
    {
        $profile = $this->normalizeName($profileName);
        $pan = $this->normalizeName($panRegisteredName ?? '');

        if ($profile === '' || $pan === '') {
            return false;
        }

        if ($profile === $pan) {
            return true;
        }

        if (str_contains($pan, $profile) || str_contains($profile, $pan)) {
            return true;
        }

        $profileTokens = $this->nameTokens($profile);
        $panTokens = $this->nameTokens($pan);

        if ($profileTokens === [] || $panTokens === []) {
            return false;
        }

        $matchCount = 0;
        foreach ($profileTokens as $token) {
            foreach ($panTokens as $panToken) {
                if ($this->tokenMatches($token, $panToken)) {
                    $matchCount++;
                    break;
                }
            }
        }

        $requiredMatches = count($profileTokens) === 1
            ? 1
            : max(2, (int) ceil(count($profileTokens) * 0.6));

        return $matchCount >= $requiredMatches;
    }

    /**
     * @return string|null Human-readable error when PAN cannot be assigned
     */
    public function validateAssignment(
        string $panNumber,
        string $profileName,
        ?string $panRegisteredName,
        ?int $excludeUserId = null,
        ?int $excludeSellerId = null,
        ?int $excludeSaleId = null
    ): ?string {
        if ($this->isPanTaken($panNumber, $excludeUserId, $excludeSellerId, $excludeSaleId)) {
            return 'This PAN number is already registered with another account.';
        }

        if (!$this->namesMatch($profileName, $panRegisteredName)) {
            return 'Profile name does not match the name registered on this PAN. Please update your profile name to match your PAN.';
        }

        return null;
    }

    public static function sellerDisplayName(Seller $seller): string
    {
        return trim(($seller->f_name ?? '') . ' ' . ($seller->l_name ?? ''));
    }

    /**
     * Verify a PAN number against the Nerofy third-party API.
     *
     * @return array{valid: bool, status: string|null, name: string|null, error: string|null}
     */
    public function verifyPanNumber(string $panNumber): array
    {
        $token = env('NEROFY_API_TOKEN');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.nerofy.in/api/v1/service/pancard/verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode(['panNumber' => $this->normalizePan($panNumber)]),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ],
        ]);

        $response = curl_exec($curl);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            return ['valid' => false, 'status' => null, 'name' => null, 'error' => 'PAN verification service unreachable: ' . $curlError];
        }

        $decoded = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE || !isset($decoded['data']['pan_status'])) {
            return ['valid' => false, 'status' => null, 'name' => null, 'error' => 'Invalid response from PAN verification service.'];
        }

        $panStatus = strtoupper(trim($decoded['data']['pan_status'] ?? ''));
        $isValid   = $panStatus === 'PAN IS VALID';

        return [
            'valid'  => $isValid,
            'status' => $decoded['data']['pan_status'] ?? null,
            'name'   => $decoded['data']['registered_name'] ?? $decoded['data']['name'] ?? null,
            'error'  => null,
        ];
    }

    private function normalizeName(string $name): string
    {
        $name = preg_replace('/[^a-zA-Z\s]/', ' ', $name);
        $name = preg_replace('/\s+/', ' ', strtoupper(trim((string) $name)));

        return $name;
    }

    /** @return list<string> */
    private function nameTokens(string $normalized): array
    {
        return array_values(array_filter(
            explode(' ', $normalized),
            static fn (string $token): bool => strlen($token) > 1
        ));
    }

    private function tokenMatches(string $a, string $b): bool
    {
        if ($a === $b) {
            return true;
        }

        return strlen($a) >= 3
            && strlen($b) >= 3
            && (str_starts_with($a, $b) || str_starts_with($b, $a));
    }
}
