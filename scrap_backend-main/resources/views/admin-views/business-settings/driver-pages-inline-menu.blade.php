<div class="inline-page-menu my-4">
    <ul class="list-unstyled">

        <li class="{{ Request::is('admin/business-settings/driver-page/driver-privacy-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-privacy-policy'])}}">{{\App\CPU\translate('privacy_policy')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/driver-page/driver-terms-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-terms-policy'])}}">{{\App\CPU\translate('Terms_and_condition')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/driver-page/driver-refund-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-refund-policy'])}}">{{\App\CPU\translate('refund_policy')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/driver-page/driver-return-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-return-policy'])}}">{{\App\CPU\translate('return_policy')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/driver-page/driver-cancellation-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-cancellation-policy'])}}">{{\App\CPU\translate('cancellation_policy')}}</a></li>
        <li class="{{ Request::is('admin/business-settings/driver-page/driver-shipping-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-shipping-policy'])}}">{{\App\CPU\translate('shipping_policy')}}</a></li>
        <!-- <li class="{{ Request::is('admin/business-settings/driver-page/driver-security-policy-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-security-policy-policy'])}}">{{\App\CPU\translate('security_policy_policy')}}</a></li> -->
        <!-- <li class="{{ Request::is('admin/business-settings/driver-page/driver-payment-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-payment-policy'])}}">{{\App\CPU\translate('payment_policy')}}</a></li> -->
        <!-- <li class="{{ Request::is('admin/business-settings/driver-page/driver-condition-of-use-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-condition-of-use-policy'])}}">{{\App\CPU\translate('condition_of_use_policy')}}</a></li> -->
        <!-- <li class="{{ Request::is('admin/business-settings/driver-page/driver-security-information') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-security-information'])}}">{{\App\CPU\translate('security_information')}}</a></li> -->
        
        <!-- <li class="{{ Request::is('admin/business-settings/driver-page/driver-delivery-guidelines') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-delivery-guidelines'])}}">{{\App\CPU\translate('Delivery Guidelines')}}</a></li> -->
        <!-- <li class="{{ Request::is('admin/business-settings/driver-page/driver-package-handling-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-package-handling-policy'])}}">{{\App\CPU\translate('Package Handling Policy')}}</a></li> -->
        <!-- <li class="{{ Request::is('admin/business-settings/driver-page/driver-customer-interaction-policy') ?'active':'' }}"><a href="{{route('admin.business-settings.driver.page',['driver-customer-interaction-policy'])}}">{{\App\CPU\translate('Customer Interaction Policy')}}</a></li> -->

    </ul>
</div>


