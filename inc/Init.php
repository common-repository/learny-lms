<?php

/**
 * @package Learny
 */

namespace Learny;

defined('ABSPATH') or die('You can not access the file directly');

final class Init
{
    public static function get_services()
    {
        return array(
            base\Bootstrap::class,
            base\CustomCapabilities::class,
            base\AjaxPosts::class,
            base\Enqueue::class,

            pages\Admin::class,

            cpt\Course::class,
            cpt\Section::class,
            cpt\Lesson::class,
            ctax\CourseCategory::class,
            ctax\CourseTag::class,
            cmb\CourseMetaBox::class,

            base\modules\Section::class,
            base\modules\Lesson::class,
            base\modules\Video::class,
            base\modules\Category::class,
            base\modules\Student::class,
            base\modules\Instructor::class,
            base\modules\Settings::class,
            base\modules\Enrolment::class,
            base\modules\Review::class,
            base\modules\Wishlist::class,
            base\modules\Payouts::class,

            base\shortcodes\Checkout::class,
            base\shortcodes\StudentDashboard::class,
            base\shortcodes\CoursePlayer::class,
            base\shortcodes\Auth::class,

            base\PostStates::class,
            base\ShortTemplates::class,

        );
    }

    public static function register_services()
    {
        foreach (self::get_services() as $class) {
            $service = self::instantiate($class);
            if (method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    private static function instantiate($class)
    {
        $service = new $class();
        return $service;
    }
}
