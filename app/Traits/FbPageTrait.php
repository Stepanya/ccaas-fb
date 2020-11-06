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

            // Europe (EU)
            // LBC Express Inc. (Malta)
            case '194538947604170':
                return env('LBC_EXPRESS_INC_MT_TOKEN');
                break;
            // LBC Express Inc. (Ireland)
            case '537562619728665':
                return env('LBC_EXPRESS_INC_IE_TOKEN');
                break;
            // LBC Express Inc. (Netherlands)
            case '569799086522819':
                return env('LBC_EXPRESS_INC_NL_TOKEN');
                break;
            // LBC Express Inc. (Spain)
            case '874926069225385':
                return env('LBC_EXPRESS_INC_ES_TOKEN');
                break;
            // LBC Express Inc. (France)
            case '940468865986851':
                return env('LBC_EXPRESS_INC_FR_TOKEN');
                break;
            // LBC Express Inc. (Italy)
            case '1404374656498593':
                return env('LBC_EXPRESS_INC_IT_TOKEN');
                break;
            // LBC Express Inc. (Germany)
            case '1481014352155430':
                return env('LBC_EXPRESS_INC_DE_TOKEN');
                break;
            // LBC Express Inc. (United Kingdom)
            case '1534101890211794':
                return env('LBC_EXPRESS_INC_GB_TOKEN');
                break;
            // LBC Express Inc. (Switzerland)
            case '1717495435160426':
                return env('LBC_EXPRESS_INC_CH_TOKEN');
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

          // Europe (EU)
          // LBC Express Inc. (Malta)
          case '194538947604170':
              return 'CS Facebook Comments - Malta';
              break;
          // LBC Express Inc. (Ireland)
          case '537562619728665':
              return 'CS Facebook Comments - Ireland';
              break;
          // LBC Express Inc. (Netherlands)
          case '569799086522819':
              return 'CS Facebook Comments - Netherlands';
              break;
          // LBC Express Inc. (Spain)
          case '874926069225385':
              return 'CS Facebook Comments - Spain';
              break;
          // LBC Express Inc. (France)
          case '940468865986851':
              return 'CS Facebook Comments - France';
              break;
          // LBC Express Inc. (Italy)
          case '1404374656498593':
              return 'CS Facebook Comments - Italy';
              break;
          // LBC Express Inc. (Germany)
          case '1481014352155430':
              return 'CS Facebook Comments - Germany';
              break;
          // LBC Express Inc. (United Kingdom)
          case '1534101890211794':
              return 'CS Facebook Comments - Great Britain';
              break;
          // LBC Express Inc. (Switzerland)
          case '1717495435160426':
              return 'CS Facebook Comments - Switzerland';
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
            
            // Europe (EU)
            // LBC Express Inc. (Malta)
            case '194538947604170':
            // LBC Express Inc. (Ireland)
            case '537562619728665':
            // LBC Express Inc. (Netherlands)
            case '569799086522819':
            // LBC Express Inc. (Spain)
            case '874926069225385':
            // LBC Express Inc. (France)
            case '940468865986851':
            // LBC Express Inc. (Italy)
            case '1404374656498593':
            // LBC Express Inc. (Germany)
            case '1481014352155430':
            // LBC Express Inc. (United Kingdom)
            case '1534101890211794':
            // LBC Express Inc. (Switzerland)
            case '1717495435160426':
                return 'LBC';
                break;
        }
    }
}
