<?php 

namespace App\Traits;

trait FbPageTrait {
    public function getPageAccessToken($page_id) {
        switch($page_id) {
            // Tritel API
            case '108729754345417':
                return env('TRITEL_API_TOKEN');
                break;
            // LBC Express Inc (PH)
            case '107092956014139':
                return env('LBC_EXPRESS_INC_PH_TOKEN');
                break;
            // North America (NA)
            // LBC Express Inc. (Canada)
            case '767635103382140':
                return env('LBC_EXPRESS_INC_CA_TOKEN');
                break;
            // LBC Express Inc. (US)
            case '1625673577698440':
                return env('LBC_EXPRESS_INC_US_TOKEN');
                break;
        }
    }

    public function getTenantName($page_id) {
        switch($page_id) {
            // Tritel API
            case '108729754345417':
                return 'Tritel';
                break;
            // LBC Express Inc (PH)
            case '107092956014139':
            // North America (NA)
            // LBC Express Inc. (Canada)
            case '767635103382140':
            // LBC Express Inc. (US)
            case '1625673577698440':
                return 'LBC';
                break;
        }
    }   
}