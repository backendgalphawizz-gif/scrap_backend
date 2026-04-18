<div class="inline-page-menu my-4">
    <ul class="list-unstyled">
        <li class="{{ Request::is('admin/settings') ?'active':'' }}"><a href="{{route('admin.settings')}}">{{\App\CPU\translate('general')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/privacy-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.privacy-policy')}}">{{\App\CPU\translate('Static Pages')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/popup-banner') ? 'active':'' }}"><a href="{{ route('admin.business-settings.popup-banner') }}">{{\App\CPU\translate('Popup_Banner')}}</a></li>
    </ul>
</div>
