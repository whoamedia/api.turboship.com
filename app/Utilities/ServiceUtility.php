<?php

namespace App\Utilities;


class ServiceUtility
{

    /**
     * USPS
     */
    const USPS_FIRST_CLASS                                      = 100;
    const USPS_PRIORITY_MAIL                                    = 101;
    const USPS_PRIORITY_MAIL_EXPRESS                            = 102;
    const USPS_PARCEL_SELECT                                    = 103;
    const USPS_LIBRARY_MAIL                                     = 104;
    const USPS_MEDIA_MAIL                                       = 105;
    const USPS_CRITICAL_MAIL                                    = 106;
    const USPS_FIRST_CLASS_INTERNATIONAL                        = 107;
    const USPS_FIRST_CLASS_PACKAGE_INTERNATIONAL                = 108;
    const USPS_PRIORITY_MAIL_INTERNATIONAL                      = 109;
    const USPS_EXPRESS_MAIL_INTERNATIONAL                       = 110;


    /**
     * UPS
     */
    const UPS_GROUND                                            = 200;
    const UPS_UPS_STANDARD                                      = 201;
    const UPS_UPS_SAVER                                         = 202;
    const UPS_EXPRESS                                           = 203;
    const UPS_EXPRESS_PLUS                                      = 204;
    const UPS_EXPEDITED                                         = 205;
    const UPS_NEXT_DAY_AIR                                      = 206;
    const UPS_NEXT_DAY_AIR_SAVER                                = 207;
    const UPS_NEXT_DAY_AIR_EARLY_AM                             = 208;
    const UPS_2ND_DAY_AIR                                       = 209;
    const UPS_2ND_DAY_AIR_AM                                    = 210;
    const UPS_3_DAY_SELECT                                      = 211;


    /**
     * UPS Mail Innovations
     */
    const UPS_MAIL_INNOVATIONS_FIRST                            = 300;
    const UPS_MAIL_INNOVATIONS_PRIORITY                         = 301;
    const UPS_MAIL_INNOVATIONS_EXPEDITED_MAIL_INNOVATIONS       = 302;
    const UPS_MAIL_INNOVATIONS_PRIORITY_MAIL_INNOVATIONS        = 303;
    const UPS_MAIL_INNOVATIONS_ECONOMY_MAIL_INNOVATIONS         = 304;

    /**
     * DHL Global Mail
     */
    const DHL_GLOBAL_MAIL_BPM_EXPEDITED_DOMESTIC                = 401;
    const DHL_GLOBAL_MAIL_BPM_GROUND_DOMESTIC                   = 402;
    const DHL_GLOBAL_MAIL_FLATS_EXPEDITED_DOMESTIC              = 403;
    const DHL_GLOBAL_MAIL_FLATS_GROUND_DOMESTIC                 = 404;
    const DHL_GLOBAL_MAIL_MEDIA_MAIL_GROUND_DOMESTIC            = 405;
    const DHL_GLOBAL_MAIL_PARCEL_PLUS_EXPEDITED_DOMESTIC        = 406;
    const DHL_GLOBAL_MAIL_PARCEL_PLUS_GROUND_DOMESTIC           = 407;
    const DHL_GLOBAL_MAIL_PARCEL_EXPEDITED_DOMESTIC             = 408;
    const DHL_GLOBAL_MAIL_PARCEL_GROUND_DOMESTIC                = 409;
    const DHL_GLOBAL_MAIL_MARKETING_PARCEL_EXPEDITED_DOMESTIC   = 410;
    const DHL_GLOBAL_MAIL_MARKETING_PARCEL_GROUND_DOMESTIC      = 411;

}