<style>
    .second {
        background: #fff !important;
    background-color: #fff !important;
    border-color: #15786C !important;
    color: #15786C !important;
    }

   
.inline-page-menu ul {
    flex-wrap: wrap; /* allow buttons to wrap on smaller screens */
}

.inline-page-menu ul li {
    margin-bottom: 0.5rem; /* vertical spacing for wrapped buttons */
}

/* Optional: mobile full width */
@media (max-width: 768px) {
    .inline-page-menu ul li {
        width: 100%;
    }
    .inline-page-menu ul li a {
        width: 100%;
        text-align: center; /* center the text */
    }
}

    </style>

<div class="inline-page-menu mb-3">
    <ul class="list-unstyled d-flex gap-2">
        <li class="{{ Request::is('admin/business-settings/terms-condition') ?'active':'' }}"><a class="btn {{ Request::is('admin/website-info/terms_condition') ?'btn-primary':'btn-secondary second' }}" href="{{route('admin.business-settings.terms-condition')}}">{{\App\CPU\translate('Terms_&_Conditions')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/privacy-policy') ?'active':'' }}"><a class="btn {{ Request::is('admin/website-info/privacy_policy') ?'btn-primary':'btn-secondary second' }}" href="{{route('admin.business-settings.privacy-policy')}}">{{\App\CPU\translate('Privacy_Policy')}}</a></li>
        
        <li class="{{ Request::is('admin/business-settings/brand-terms-condition') ?'active':'' }}"><a class="btn {{ Request::is('admin/website-info/brand-terms_condition') ?'btn-primary':'btn-secondary second' }}" href="{{route('admin.business-settings.brand-terms-condition')}}">{{\App\CPU\translate('Brand Terms_&_Conditions')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/brand-privacy-policy') ?'active':'' }}"><a class="btn {{ Request::is('admin/website-info/brand-privacy_policy') ?'btn-primary':'btn-secondary second' }}" href="{{route('admin.business-settings.brand-privacy-policy')}}">{{\App\CPU\translate('Brand Privacy_Policy')}}</a></li>
    </ul>
</div>
