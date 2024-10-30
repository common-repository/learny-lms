<?php

/**
 * @package Learny
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

class Activate extends BaseController
{
    // Main method that activates the plugin
    public static function activate()
    {
        self::add_custom_roles();
        self::setup_tables();
        self::create_mandatory_pages();
        flush_rewrite_rules();
        self::push_default_data();

        // ADMIN CAPABILITIES
        $customCapabilities = new CustomCapabilities();
        $customCapabilities->addCustomCapabilitiesToAdmin();
    }

    // Method for adding custom roles
    private static function add_custom_roles()
    {
        foreach (self::$custom_roles as $custom_role) {
            remove_role($custom_role['role']);

            add_role($custom_role['role'], $custom_role['display_name'], $custom_role['caps']);
        }
    }

    // Method for setting up tables required for the plugin into wp database
    private static function setup_tables()
    {
        self::setup_currencies_table();
        self::setup_enrolment_table();
        self::setup_payment_table();
        self::setup_payout_table();
        self::setup_review_table();
        self::setup_watch_history_table();
        self::setup_wishlist_table();
    }

    // DATABASE TABLE CREATION
    private static function setup_currencies_table()
    {
        $table = self::$tables['currencies'];
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(255) DEFAULT NULL,
            `code` varchar(255) DEFAULT NULL,
            `symbol` varchar(255) DEFAULT NULL,
            `paypal_supported` int(11) DEFAULT NULL,
            `stripe_supported` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        global $wpdb;
        $wpdb->query($sql);
    }
    private static function setup_enrolment_table()
    {
        $table = self::$tables['enrolment'];
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            `enrolment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `enrolment_user_id` int(11) DEFAULT NULL,
            `enrolment_course_id` int(11) DEFAULT NULL,
            `enrolment_date` int(11) DEFAULT NULL,
            PRIMARY KEY (`enrolment_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        global $wpdb;
        $wpdb->query($sql);
    }
    private static function setup_payment_table()
    {
        $table = self::$tables['payment'];
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            `payment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `payment_user_id` int(11) DEFAULT NULL,
            `payment_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
            `payment_course_id` int(11) DEFAULT NULL,
            `payment_amount` double DEFAULT NULL,
            `payment_date` int(11) DEFAULT NULL,
            `payment_admin_revenue` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `payment_instructor_revenue` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `payment_paypal_pay_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            `payment_session_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
            PRIMARY KEY (`payment_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        global $wpdb;
        $wpdb->query($sql);
    }
    private static function setup_payout_table()
    {
        $table = self::$tables['payout'];
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            `payout_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `payout_instructor_id` int(11) DEFAULT NULL,
            `payout_payment_type` varchar(255) DEFAULT NULL,
            `payout_amount` double DEFAULT NULL,
            `payout_date_added` int(11) DEFAULT NULL,
            `payout_last_modified` int(11) DEFAULT NULL,
            `payout_status` int(11) DEFAULT '0',
            PRIMARY KEY (`payout_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        global $wpdb;
        $wpdb->query($sql);
    }
    private static function setup_review_table()
    {
        $table = self::$tables['review'];
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            `review_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `review_user_id` int(11) DEFAULT NULL,
            `review_rating` int(11) DEFAULT NULL,
            `review_details` longtext COLLATE utf8_unicode_ci,
            `review_course_id` int(11) DEFAULT NULL,
            `review_date` int(11) DEFAULT NULL,
            PRIMARY KEY (`review_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        global $wpdb;
        $wpdb->query($sql);
    }
    private static function setup_watch_history_table()
    {
        $table = self::$tables['watch_history'];
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            `watch_history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `watch_history_user_id` int(11) DEFAULT NULL,
            `watch_history_course_id` int(11) DEFAULT NULL,
            `watch_history_completed_lessons` longtext COLLATE utf8_unicode_ci,
            `watch_history_last_played_lesson_id` int(11) DEFAULT NULL,
            PRIMARY KEY (`watch_history_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        global $wpdb;
        $wpdb->query($sql);
    }
    private static function setup_wishlist_table()
    {
        $table = self::$tables['wishlist'];
        $sql = "CREATE TABLE IF NOT EXISTS $table (
            `wishlist_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `wishlist_user_id` int(11) DEFAULT NULL,
            `wishlist_course_id` int(11) DEFAULT NULL,
            `wishlist_date` int(11) DEFAULT NULL,
            PRIMARY KEY (`wishlist_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

        global $wpdb;
        $wpdb->query($sql);
    }


    /**
     * INSERT DEFAULT DATA TO TABLES
     *
     * @return void
     */
    private static function push_default_data()
    {
        self::push_to_currencies_table();
    }

    /**
     * PUSHING DATA
     *
     * @return void
     */
    private static function push_to_currencies_table()
    {
        global $wpdb;
        $table = self::$tables['currencies'];
        $wpdb->query("TRUNCATE TABLE $table;");

        $currency_list = array(
            array("id" => 1, "name" => "US Dollar", "code" => "USD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 2, "name" => "Albanian Lek", "code" => "ALL", "symbol" => "Lek", "paypal_supported" => 0, "stripe_supported" => 1),
            array("id" => 3, "name" => "Algerian Dinar", "code" => "DZD", "symbol" => "دج", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 4, "name" => "Angolan Kwanza", "code" => "AOA", "symbol" => "Kz", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 5, "name" => "Argentine Peso", "code" => "ARS", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 6, "name" => "Armenian Dram", "code" => "AMD", "symbol" => "֏", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 7, "name" => "Aruban Florin", "code" => "AWG", "symbol" => "ƒ", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 8, "name" => "Australian Dollar", "code" => "AUD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 9, "name" => "Azerbaijani Manat", "code" => "AZN", "symbol" => "m", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 10, "name" => "Bahamian Dollar", "code" => "BSD", "symbol" => "B$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 11, "name" => "Bahraini Dinar", "code" => "BHD", "symbol" => ".د.ب", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 12, "name" => "Bangladeshi Taka", "code" => "BDT", "symbol" => "৳", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 13, "name" => "Barbadian Dollar", "code" => "BBD", "symbol" => "Bds$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 14, "name" => "Belarusian Ruble", "code" => "BYR", "symbol" => "Br", "paypal_supported" => 0, "stripe_supported" => 0),
            array("id" => 15, "name" => "Belgian Franc", "code" => "BEF", "symbol" => "fr", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 16, "name" => "Belize Dollar", "code" => "BZD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 17, "name" => "Bermudan Dollar", "code" => "BMD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 18, "name" => "Bhutanese Ngultrum", "code" => "BTN", "symbol" => "Nu.", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 19, "name" => "Bitcoin", "code" => "BTC", "symbol" => "฿", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 20, "name" => "Bolivian Boliviano", "code" => "BOB", "symbol" => "Bs.", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 21, "name" => "Bosnia", "code" => "BAM", "symbol" => "KM", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 22, "name" => "Botswanan Pula", "code" => "BWP", "symbol" => "P", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 23, "name" => "Brazilian Real", "code" => "BRL", "symbol" => "R$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 24, "name" => "British Pound Sterling", "code" => "GBP", "symbol" => "£", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 25, "name" => "Brunei Dollar", "code" => "BND", "symbol" => "B$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 26, "name" => "Bulgarian Lev", "code" => "BGN", "symbol" => "Лв.", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 27, "name" => "Burundian Franc", "code" => "BIF", "symbol" => "FBu", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 28, "name" => "Cambodian Riel", "code" => "KHR", "symbol" => "KHR", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 29, "name" => "Canadian Dollar", "code" => "CAD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 30, "name" => "Cape Verdean Escudo", "code" => "CVE", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 31, "name" => "Cayman Islands Dollar", "code" => "KYD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 32, "name" => "CFA Franc BCEAO", "code" => "XOF", "symbol" => "CFA", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 33, "name" => "CFA Franc BEAC", "code" => "XAF", "symbol" => "FCFA", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 34, "name" => "CFP Franc", "code" => "XPF", "symbol" => "₣", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 35, "name" => "Chilean Peso", "code" => "CLP", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 36, "name" => "Chinese Yuan", "code" => "CNY", "symbol" => "¥", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 37, "name" => "Colombian Peso", "code" => "COP", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 38, "name" => "Comorian Franc", "code" => "KMF", "symbol" => "CF", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 39, "name" => "Congolese Franc", "code" => "CDF", "symbol" => "FC", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 40, "name" => "Costa Rican ColÃ³n", "code" => "CRC", "symbol" => "₡", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 41, "name" => "Croatian Kuna", "code" => "HRK", "symbol" => "kn", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 42, "name" => "Cuban Convertible Peso", "code" => "CUC", "symbol" => "$, CUC", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 43, "name" => "Czech Republic Koruna", "code" => "CZK", "symbol" => "Kč", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 44, "name" => "Danish Krone", "code" => "DKK", "symbol" => "Kr.", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 45, "name" => "Djiboutian Franc", "code" => "DJF", "symbol" => "Fdj", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 46, "name" => "Dominican Peso", "code" => "DOP", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 47, "name" => "East Caribbean Dollar", "code" => "XCD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 48, "name" => "Egyptian Pound", "code" => "EGP", "symbol" => "ج.م", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 49, "name" => "Eritrean Nakfa", "code" => "ERN", "symbol" => "Nfk", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 50, "name" => "Estonian Kroon", "code" => "EEK", "symbol" => "kr", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 51, "name" => "Ethiopian Birr", "code" => "ETB", "symbol" => "Nkf", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 52, "name" => "Euro", "code" => "EUR", "symbol" => "€", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 53, "name" => "Falkland Islands Pound", "code" => "FKP", "symbol" => "£", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 54, "name" => "Fijian Dollar", "code" => "FJD", "symbol" => "FJ$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 55, "name" => "Gambian Dalasi", "code" => "GMD", "symbol" => "D", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 56, "name" => "Georgian Lari", "code" => "GEL", "symbol" => "ლ", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 57, "name" => "German Mark", "code" => "DEM", "symbol" => "DM", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 58, "name" => "Ghanaian Cedi", "code" => "GHS", "symbol" => "GH₵", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 59, "name" => "Gibraltar Pound", "code" => "GIP", "symbol" => "£", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 60, "name" => "Greek Drachma", "code" => "GRD", "symbol" => "₯, Δρχ, Δρ", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 61, "name" => "Guatemalan Quetzal", "code" => "GTQ", "symbol" => "Q", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 62, "name" => "Guinean Franc", "code" => "GNF", "symbol" => "FG", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 63, "name" => "Guyanaese Dollar", "code" => "GYD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 64, "name" => "Haitian Gourde", "code" => "HTG", "symbol" => "G", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 65, "name" => "Honduran Lempira", "code" => "HNL", "symbol" => "L", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 66, "name" => "Hong Kong Dollar", "code" => "HKD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 67, "name" => "Hungarian Forint", "code" => "HUF", "symbol" => "Ft", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 68, "name" => "Icelandic KrÃ³na", "code" => "ISK", "symbol" => "kr", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 69, "name" => "Indian Rupee", "code" => "INR", "symbol" => "₹", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 70, "name" => "Indonesian Rupiah", "code" => "IDR", "symbol" => "Rp", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 71, "name" => "Iranian Rial", "code" => "IRR", "symbol" => "﷼", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 72, "name" => "Iraqi Dinar", "code" => "IQD", "symbol" => "د.ع", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 73, "name" => "Israeli New Sheqel", "code" => "ILS", "symbol" => "₪", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 74, "name" => "Italian Lira", "code" => "ITL", "symbol" => "L,£", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 75, "name" => "Jamaican Dollar", "code" => "JMD", "symbol" => "J$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 76, "name" => "Japanese Yen", "code" => "JPY", "symbol" => "¥", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 77, "name" => "Jordanian Dinar", "code" => "JOD", "symbol" => "ا.د", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 78, "name" => "Kazakhstani Tenge", "code" => "KZT", "symbol" => "лв", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 79, "name" => "Kenyan Shilling", "code" => "KES", "symbol" => "KSh", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 80, "name" => "Kuwaiti Dinar", "code" => "KWD", "symbol" => "ك.د", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 81, "name" => "Kyrgystani Som", "code" => "KGS", "symbol" => "лв", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 82, "name" => "Laotian Kip", "code" => "LAK", "symbol" => "₭", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 83, "name" => "Latvian Lats", "code" => "LVL", "symbol" => "Ls", "paypal_supported" => 0, "stripe_supported" => 0),
            array("id" => 84, "name" => "Lebanese Pound", "code" => "LBP", "symbol" => "£", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 85, "name" => "Lesotho Loti", "code" => "LSL", "symbol" => "L", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 86, "name" => "Liberian Dollar", "code" => "LRD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 87, "name" => "Libyan Dinar", "code" => "LYD", "symbol" => "د.ل", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 88, "name" => "Lithuanian Litas", "code" => "LTL", "symbol" => "Lt", "paypal_supported" => 0, "stripe_supported" => 0),
            array("id" => 89, "name" => "Macanese Pataca", "code" => "MOP", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 90, "name" => "Macedonian Denar", "code" => "MKD", "symbol" => "ден", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 91, "name" => "Malagasy Ariary", "code" => "MGA", "symbol" => "Ar", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 92, "name" => "Malawian Kwacha", "code" => "MWK", "symbol" => "MK", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 93, "name" => "Malaysian Ringgit", "code" => "MYR", "symbol" => "RM", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 94, "name" => "Maldivian Rufiyaa", "code" => "MVR", "symbol" => "Rf", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 95, "name" => "Mauritanian Ouguiya", "code" => "MRO", "symbol" => "MRU", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 96, "name" => "Mauritian Rupee", "code" => "MUR", "symbol" => "₨", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 97, "name" => "Mexican Peso", "code" => "MXN", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 98, "name" => "Moldovan Leu", "code" => "MDL", "symbol" => "L", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 99, "name" => "Mongolian Tugrik", "code" => "MNT", "symbol" => "₮", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 100, "name" => "Moroccan Dirham", "code" => "MAD", "symbol" => "MAD", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 101, "name" => "Mozambican Metical", "code" => "MZM", "symbol" => "MT", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 102, "name" => "Myanmar Kyat", "code" => "MMK", "symbol" => "K", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 103, "name" => "Namibian Dollar", "code" => "NAD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 104, "name" => "Nepalese Rupee", "code" => "NPR", "symbol" => "₨", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 105, "name" => "Netherlands Antillean Guilder", "code" => "ANG", "symbol" => "ƒ", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 106, "name" => "New Taiwan Dollar", "code" => "TWD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 107, "name" => "New Zealand Dollar", "code" => "NZD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 108, "name" => "Nicaraguan CÃ³rdoba", "code" => "NIO", "symbol" => "C$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 109, "name" => "Nigerian Naira", "code" => "NGN", "symbol" => "₦", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 110, "name" => "North Korean Won", "code" => "KPW", "symbol" => "₩", "paypal_supported" => 0, "stripe_supported" => 0),
            array("id" => 111, "name" => "Norwegian Krone", "code" => "NOK", "symbol" => "kr", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 112, "name" => "Omani Rial", "code" => "OMR", "symbol" => ".ع.ر", "paypal_supported" => 0, "stripe_supported" => 0),
            array("id" => 113, "name" => "Pakistani Rupee", "code" => "PKR", "symbol" => "₨", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 114, "name" => "Panamanian Balboa", "code" => "PAB", "symbol" => "B/.", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 115, "name" => "Papua New Guinean Kina", "code" => "PGK", "symbol" => "K", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 116, "name" => "Paraguayan Guarani", "code" => "PYG", "symbol" => "₲", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 117, "name" => "Peruvian Nuevo Sol", "code" => "PEN", "symbol" => "S/.", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 118, "name" => "Philippine Peso", "code" => "PHP", "symbol" => "₱", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 119, "name" => "Polish Zloty", "code" => "PLN", "symbol" => "zł", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 120, "name" => "Qatari Rial", "code" => "QAR", "symbol" => "ق.ر", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 121, "name" => "Romanian Leu", "code" => "RON", "symbol" => "lei", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 122, "name" => "Russian Ruble", "code" => "RUB", "symbol" => "₽", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 123, "name" => "Rwandan Franc", "code" => "RWF", "symbol" => "FRw", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 124, "name" => "Salvadoran ColÃ³n", "code" => "SVC", "symbol" => "₡", "paypal_supported" => 0, "stripe_supported" => 0),
            array("id" => 125, "name" => "Samoan Tala", "code" => "WST", "symbol" => "SAT", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 126, "name" => "Saudi Riyal", "code" => "SAR", "symbol" => "﷼", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 127, "name" => "Serbian Dinar", "code" => "RSD", "symbol" => "din", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 128, "name" => "Seychellois Rupee", "code" => "SCR", "symbol" => "SRe", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 129, "name" => "Sierra Leonean Leone", "code" => "SLL", "symbol" => "Le", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 130, "name" => "Singapore Dollar", "code" => "SGD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 131, "name" => "Slovak Koruna", "code" => "SKK", "symbol" => "Sk", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 132, "name" => "Solomon Islands Dollar", "code" => "SBD", "symbol" => "Si$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 133, "name" => "Somali Shilling", "code" => "SOS", "symbol" => "Sh.so.", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 134, "name" => "South African Rand", "code" => "ZAR", "symbol" => "R", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 135, "name" => "South Korean Won", "code" => "KRW", "symbol" => "₩", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 136, "name" => "Special Drawing Rights", "code" => "XDR", "symbol" => "SDR", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 137, "name" => "Sri Lankan Rupee", "code" => "LKR", "symbol" => "Rs", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 138, "name" => "St. Helena Pound", "code" => "SHP", "symbol" => "£", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 139, "name" => "Sudanese Pound", "code" => "SDG", "symbol" => ".س.ج", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 140, "name" => "Surinamese Dollar", "code" => "SRD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 141, "name" => "Swazi Lilangeni", "code" => "SZL", "symbol" => "E", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 142, "name" => "Swedish Krona", "code" => "SEK", "symbol" => "kr", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 143, "name" => "Swiss Franc", "code" => "CHF", "symbol" => "CHf", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 144, "name" => "Syrian Pound", "code" => "SYP", "symbol" => "LS", "paypal_supported" => 0, "stripe_supported" => 0),
            array("id" => 145, "name" => "São Tomé and Príncipe Dobra", "code" => "STD", "symbol" => "Db", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 146, "name" => "Tajikistani Somoni", "code" => "TJS", "symbol" => "SM", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 147, "name" => "Tanzanian Shilling", "code" => "TZS", "symbol" => "TSh", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 148, "name" => "Thai Baht", "code" => "THB", "symbol" => "฿", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 149, "name" => "Tongan pa'anga", "code" => "TOP", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 150, "name" => "Trinidad & Tobago Dollar", "code" => "TTD", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 151, "name" => "Tunisian Dinar", "code" => "TND", "symbol" => "ت.د", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 152, "name" => "Turkish Lira", "code" => "TRY", "symbol" => "₺", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 153, "name" => "Turkmenistani Manat", "code" => "TMT", "symbol" => "T", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 154, "name" => "Ugandan Shilling", "code" => "UGX", "symbol" => "USh", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 155, "name" => "Ukrainian Hryvnia", "code" => "UAH", "symbol" => "₴", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 156, "name" => "United Arab Emirates Dirham", "code" => "AED", "symbol" => "إ.د", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 157, "name" => "Uruguayan Peso", "code" => "UYU", "symbol" => "$", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 158, "name" => "Afghan Afghani", "code" => "AFA", "symbol" => "؋", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 159, "name" => "Uzbekistan Som", "code" => "UZS", "symbol" => "лв", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 160, "name" => "Vanuatu Vatu", "code" => "VUV", "symbol" => "VT", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 161, "name" => "Venezuelan BolÃvar", "code" => "VEF", "symbol" => "Bs", "paypal_supported" => 0, "stripe_supported" => 0),
            array("id" => 162, "name" => "Vietnamese Dong", "code" => "VND", "symbol" => "₫", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 163, "name" => "Yemeni Rial", "code" => "YER", "symbol" => "﷼", "paypal_supported" => 1, "stripe_supported" => 1),
            array("id" => 164, "name" => "Zambian Kwacha", "code" => "ZMK", "symbol" => "ZK", "paypal_supported" => 1, "stripe_supported" => 1)
        );
        
        foreach($currency_list as $data){
            $wpdb->insert($table, $data);
        }
    }

    /**
     * CREATING MANDATORY PAGES
     *
     * @return void
     */
    private static function create_mandatory_pages()
    {
        foreach (self::$plugin_pages as $page_id => $page_slug) {
            $page_guid = site_url() . "/$page_slug";
            $page_title = ucfirst(str_replace('-', ' ', $page_slug));

            $my_post  = array(
                'post_title'     => $page_title,
                'post_type'      => 'page',
                'post_name'      => $page_slug,
                'post_content'   => '',
                'post_status'    => 'publish',
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
                'post_author'    => 1,
                'menu_order'     => 0,
                'guid'           => $page_guid
            );

            $page_ID = wp_insert_post($my_post, FALSE);
            update_option($page_id, sanitize_text_field($page_ID));
        }
    }
}
