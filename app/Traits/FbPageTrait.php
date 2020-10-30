<?php

namespace App\Traits;

trait FbPageTrait {
    private function getPageAccessToken($page_id) {
        switch($page_id) {
            // Test USA 1
            case '101525328434170':
                return env('TEST_USA_TOKEN');
                break;
            // Test Canada 1
            case '104482258133928':
                return env('TEST_CANADA_TOKEN');
                break;
            // Test Japan 1
            case '100936665160720':
                return env('TEST_JAPAN_TOKEN');
                break;

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

    private function getAssigneeValue($page_id) {
      switch($page_id) {
          // Test USA 1
          case '101525328434170':
              return 'CS Facebook Comments - USA';
              break;
          // Test Canada 1
          case '104482258133928':
              return 'CS Facebook Comments - Canada';
              break;
          // Test Japan 1
          case '100936665160720':
              return 'CS Facebook Comments - Japan';
              break;

          // Tritel API
          case '108729754345417':
              return 'CS Facebook Comments';
              break;
          // LBC Express Inc (PH)
          case '107092956014139':
              return 'CS Facebook Comments - Philippines';
              break;
          // North America (NA)
          // LBC Express Inc. (Canada)
          case '767635103382140':
              return 'CS Facebook Comments - Canada';
              break;
          // LBC Express Inc. (US)
          case '1625673577698440':
              return 'CS Facebook Comments - USA';
              break;
      }
    }

    private function getTenantName($page_id) {
        switch($page_id) {
            // Test USA 1
            case '101525328434170':
            // Test Canada 1
            case '104482258133928':
            // Test Japan 1
            case '100936665160720':

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
