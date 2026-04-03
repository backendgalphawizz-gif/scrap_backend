<div class="inline-page-menu mb-3">
    <ul class="list-unstyled d-flex gap-2">
        <li class="{{ Request::is('admin/business-settings/terms-condition') ?'active':'' }}"><a class="btn btn-primary" href="{{route('admin.business-settings.terms-condition')}}">{{\App\CPU\translate('Terms_&_Conditions')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/privacy-policy') ?'active':'' }}"><a class="btn btn-primary" href="{{route('admin.business-settings.privacy-policy')}}">{{\App\CPU\translate('Privacy_Policy')}}</a></li>
        
        <li class="{{ Request::is('admin/business-settings/brand-terms-condition') ?'active':'' }}"><a class="btn btn-primary" href="{{route('admin.business-settings.brand-terms-condition')}}">{{\App\CPU\translate('Brand Terms_&_Conditions')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/brand-privacy-policy') ?'active':'' }}"><a class="btn btn-primary" href="{{route('admin.business-settings.brand-privacy-policy')}}">{{\App\CPU\translate('Brand Privacy_Policy')}}</a></li>
    </ul>
</div>
