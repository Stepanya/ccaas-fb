<?php

namespace App\Traits\V1\LBC;

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
            // Tritel API - 4
            case '107333891412816':
                return env('TRITEL_API_4_TOKEN');
                break;
            // Tritel API - 5
            case '107073368109732':
                return env('TRITEL_API_5_TOKEN');
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

            // Asia Pacific (APAC)
            // LBC Express Inc. (Singapore)
            case '193667640971381':
                return env('LBC_EXPRESS_INC_SG_TOKEN');
                break;
            // LBC Express Inc. (Malaysia)
            case '915755551843053':
                return env('LBC_EXPRESS_INC_MY_TOKEN');
                break;
            // LBC Express Inc. (Taiwan)
            case '1015098498540775':
                return env('LBC_EXPRESS_INC_TW_TOKEN');
                break;
            // LBC Express Inc. (Hong Kong)
            case '1521025514880268':
                return env('LBC_EXPRESS_INC_HK_TOKEN');
                break;
            // LBC Express Inc. (Australia)
            case '929910540390547':
                return env('LBC_EXPRESS_INC_AU_TOKEN');
                break;
            // LBC Express Inc. (Macau)
            case '541076676228078':
                return env('LBC_EXPRESS_INC_MO_TOKEN');
                break;
            // LBC Express Inc. (Saipan)
            case '985990068161350':
                return env('LBC_EXPRESS_INC_MP_TOKEN');
                break;
            // LBC Express Inc. (South Korea)
            case '195105950654260':
                return env('LBC_EXPRESS_INC_KR_TOKEN');
                break;
            // LBC Express Inc. (Japan)
            case '211730125525677':
                return env('LBC_EXPRESS_INC_JP_TOKEN');
                break;
            // LBC Express Inc. (Brunei Darussalam)
            case '1494724230646828':
                return env('LBC_EXPRESS_INC_BN_TOKEN');
                break;
            // LBC Express Inc. (Israel)
            case '104693944254557':
                return env('LBC_EXPRESS_INC_IL_TOKEN');
                break;
            // Mermaid Co. Ltd.
            case '167281136725655':
                return env('MERMAID_CO_LTD_TOKEN');
                break;
            // TRANSTECH Balik Bayan Box
            case '158667767532875':
                return env('TRANSTECH_BALIKBAYAN_BOX_TOKEN');
                break;

            // Middle East (ME)
            // LBC Express Inc. (UAE)
            case '115471998818232':
                return env('LBC_EXPRESS_INC_AE_TOKEN');
                break;
            // LBC Express Inc. (Kuwait)
            case '1441079642807796':
                return env('LBC_EXPRESS_INC_KW_TOKEN');
                break;
            // LBC Express Inc. (KSA)
            case '1498678490434816':
                return env('LBC_EXPRESS_INC_SA_TOKEN');
                break;
            // LBC Express Inc. (Qatar)
            case '1541766656113891':
                return env('LBC_EXPRESS_INC_QA_TOKEN');
                break;
            // LBC Express Inc. (Bahrain)
            case '1667892016757806':
                return env('LBC_EXPRESS_INC_BH_TOKEN');
                break;

            // LBC Guam
            // LBC Express Inc. (Guam)
            case '1660800674176811':
                return env('LBC_EXPRESS_INC_GUAM_TOKEN');
                break;

            // SoShop! by LBC
            // SoShop! by LBC
            case '104578181347878':
                return env('SOSHOP_BY_LBC_TOKEN');
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
          // case '108729754345417':
          // Tritel API - 4
          case '107333891412816':
              return 'CS Facebook Comments - Canada';
              break;
          // Tritel API - 5
          case '107073368109732':
              return 'CS Facebook Comments - USA';
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

          // Asia Pacific (APAC)
          // LBC Express Inc. (Singapore)
          case '193667640971381':
              return 'CS Facebook Comments - Singapore';
              break;
          // LBC Express Inc. (Malaysia)
          case '915755551843053':
              return 'CS Facebook Comments - Malaysia';
              break;
          // LBC Express Inc. (Taiwan)
          case '1015098498540775':
              return 'CS Facebook Comments - Taiwan';
              break;
          // LBC Express Inc. (Hong Kong)
          case '1521025514880268':
              return 'CS Facebook Comments - Hongkong';
              break;
          // LBC Express Inc. (Australia)
          case '929910540390547':
              return 'CS Facebook Comments - Australia';
              break;
          // LBC Express Inc. (Macau)
          case '541076676228078':
              return 'CS Facebook Comments - Macau';
              break;
          // LBC Express Inc. (Saipan)
          case '985990068161350':
              return 'CS Facebook Comments - Saipan';
              break;
          // LBC Express Inc. (South Korea)
          case '195105950654260':
              return 'CS Facebook Comments - South Korea';
              break;
          // LBC Express Inc. (Japan)
          case '211730125525677':
              return 'CS Facebook Comments - Japan';
              break;
          // LBC Express Inc. (Brunei Darussalam)
          case '1494724230646828':
              return 'CS Facebook Comments - Brunei';
              break;
          // LBC Express Inc. (Israel)
          case '104693944254557':
              return 'CS Facebook Comments - Israel';
              break;
          // Mermaid Co. Ltd.
          case '167281136725655':
              return 'CS Facebook Comments - Mermaid';
              break;
          // TRANSTECH Balik Bayan Box
          case '158667767532875':
              return 'CS Facebook Comments - Transtech';
              break;

          // Middle East (ME)
          // LBC Express Inc. (UAE)
          case '115471998818232':
              return 'CS Facebook Comments - UAE';
              break;
          // LBC Express Inc. (Kuwait)
          case '1441079642807796':
              return 'CS Facebook Comments - Kuwait';
              break;
          // LBC Express Inc. (KSA)
          case '1498678490434816':
              return 'CS Facebook Comments - KSA';
              break;
          // LBC Express Inc. (Qatar)
          case '1541766656113891':
              return 'CS Facebook Comments - Qatar';
              break;
          // LBC Express Inc. (Bahrain)
          case '1667892016757806':
              return 'CS Facebook Comments - Bahrain';
              break;

          // LBC Guam
          // LBC Express Inc. (Guam)
          case '1660800674176811':
              return 'CS Facebook Comments - Guam';
              break;

          // SoShop! by LBC
          // SoShop! by LBC
          case '104578181347878':
              return 'Facebook Comments - LBC So Shop';
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
            // Tritel API - 4
            case '107333891412816':
            // Tritel API - 5
            case '107073368109732':
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

            // Asia Pacific (APAC)
            // LBC Express Inc. (Singapore)
            case '193667640971381':
            // LBC Express Inc. (Malaysia)
            case '915755551843053':
            // LBC Express Inc. (Taiwan)
            case '1015098498540775':
            // LBC Express Inc. (Hong Kong)
            case '1521025514880268':
            // LBC Express Inc. (Australia)
            case '929910540390547':
            // LBC Express Inc. (Macau)
            case '541076676228078':
            // LBC Express Inc. (Saipan)
            case '985990068161350':
            // LBC Express Inc. (South Korea)
            case '195105950654260':
            // LBC Express Inc. (Japan)
            case '211730125525677':
            // LBC Express Inc. (Brunei Darussalam)
            case '1494724230646828':
            // LBC Express Inc. (Israel)
            case '104693944254557':
            // Mermaid Co. Ltd.
            case '167281136725655':
            // TRANSTECH Balik Bayan Box
            case '158667767532875':

            // Middle East (ME)
            // LBC Express Inc. (UAE)
            case '115471998818232':
            // LBC Express Inc. (Kuwait)
            case '1441079642807796':
            // LBC Express Inc. (KSA)
            case '1498678490434816':
            // LBC Express Inc. (Qatar)
            case '1541766656113891':
            // LBC Express Inc. (Bahrain)
            case '1667892016757806':

            // LBC Guam
            // LBC Express Inc. (Guam)
            case '1660800674176811':

            // SoShop! by LBC
            // SoShop! by LBC
            case '104578181347878':
                return 'LBC';
                break;
        }
    }
}
