<?php

namespace App\Permissions;

use ReflectionClass;

class PermissionRegistry
{
    // =========================role Permissions=========================
    const ADMIN_ROLES_INDEX = 'roles.index';

    const ADMIN_ROLES_STORE = 'roles.store';

    const ADMIN_ROLES_SHOW = 'roles.show';

    const ADMIN_ROLES_UPDATE = 'roles.update';

    const ADMIN_ROLES_DESTROY = 'roles.destroy';
    // =========================role Permissions========================

    // =========================permission========================
    const ADMIN_PERMISSIONS_INDEX = 'permissions.index';

    const ADMIN_PERMISSIONS_SHOW = 'permissions.show';

    const ADMIN_PERMISSIONS_DESTROY = 'permissions.destroy';
    // =========================permission========================

    // =========================attribute Permissions=========================
    public const ADMIN_ATTRIBUTES_INDEX = 'admin.attributes.index';

    public const ADMIN_ATTRIBUTES_STORE = 'admin.attributes.store';

    public const ADMIN_ATTRIBUTES_SHOW = 'admin.attributes.show';

    public const ADMIN_ATTRIBUTES_UPDATE = 'admin.attributes.update';

    public const ADMIN_ATTRIBUTES_DESTROY = 'admin.attributes.destroy';
    // =========================attribute Permissions===========================

    // =========================city Permissions===========================
    public const ADMIN_CITIES_INDEX = 'admin.cities.index';

    public const ADMIN_CITIES_STORE = 'admin.cities.store';

    public const ADMIN_CITIES_SHOW = 'admin.cities.show';

    public const ADMIN_CITIES_UPDATE = 'admin.cities.update';

    public const ADMIN_CITIES_DESTROY = 'admin.cities.destroy';
    // =========================city Permissions===========================

    // =========================== Country Permissions ===========================
    const COUNTRY_INDEX = 'country.index';

    const COUNTRY_STORE = 'country.store';

    const COUNTRY_SHOW = 'country.show';

    const COUNTRY_UPDATE = 'country.update';

    const COUNTRY_DESTROY = 'country.destroy';
    // =========================== Country Permissions ===========================

    // ===========================property Permissions ===========================
    public const ADMIN_PROPERTIES_INDEX = 'admin.properties.index';

    public const ADMIN_PROPERTIES_STORE = 'admin.properties.store';

    public const ADMIN_PROPERTIES_SHOW = 'admin.properties.show';

    public const ADMIN_PROPERTIES_UPDATE = 'admin.properties.update';

    public const ADMIN_PROPERTIES_DESTROY = 'admin.properties.destroy';
    // ===========================property Permissions ===========================

    // ===========================property_type Permissions =======================
    public const ADMIN_PROPERTY_TYPES_INDEX = 'admin.property_types.index';

    public const ADMIN_PROPERTY_TYPES_STORE = 'admin.property_types.store';

    public const ADMIN_PROPERTY_TYPES_SHOW = 'admin.property_types.show';

    public const ADMIN_PROPERTY_TYPES_UPDATE = 'admin.property_types.update';

    public const ADMIN_PROPERTY_TYPES_DESTROY = 'admin.property_types.destroy';
    // ===========================property_type Permissions =======================

    // ===========================state Permissions ======================
    public const ADMIN_STATES_INDEX = 'admin.states.index';

    public const ADMIN_STATES_STORE = 'admin.states.store';

    public const ADMIN_STATES_SHOW = 'admin.states.show';

    public const ADMIN_STATES_UPDATE = 'admin.states.update';

    public const ADMIN_STATES_DESTROY = 'admin.states.destroy';
    // ===========================state Permissions ======================

    // ================= Units Permissions =================
    public const ADMIN_UNITS_INDEX = 'admin.units.index';

    public const ADMIN_UNITS_SHOW = 'admin.units.show';

    public const ADMIN_UNITS_STORE = 'admin.units.store';

    public const ADMIN_UNITS_UPDATE = 'admin.units.update';

    public const ADMIN_UNITS_DESTROY = 'admin.units.destroy';

    // ================= Units Permissions =================

    // ================= Admin orders Permissions =================

    public const ADMIN_ORDERS_INDEX = 'admin.orders.index';

    public const ADMIN_ORDERS_SHOW = 'admin.orders.show';

    public const ADMIN_ORDERS_APPROVE = 'admin.orders.approve';

    public const ADMIN_ORDERS_REJECT = 'admin.orders.reject';

    // ================= Admin orders Permissions =================

    // ==================== Developer Permissions =================
    public const ADMIN_DEVELOPERS_INDEX = 'admin.developers.index';

    public const ADMIN_DEVELOPERS_STORE = 'admin.developers.store';

    public const ADMIN_DEVELOPERS_SHOW = 'admin.developers.show';

    public const ADMIN_DEVELOPERS_UPDATE = 'admin.developers.update';

    public const ADMIN_DEVELOPERS_DESTROY = 'admin.developers.destroy';
    // ==================== Developer Permissions =================

    // ==================== Contact Permissions ======================
    public const ADMIN_CONTACTS_INDEX = 'admin.contacts.index';

    public const ADMIN_CONTACTS_SHOW = 'admin.contacts.show';

    public const ADMIN_CONTACTS_DESTROY = 'admin.contacts.destroy';
    // ==================== Contact Permissions ======================

    // ==================== Compound Permissions ======================
    public const ADMIN_COMPOUNDS_INDEX = 'admin.compounds.index';

    public const ADMIN_COMPOUNDS_STORE = 'admin.compounds.store';

    public const ADMIN_COMPOUNDS_SHOW = 'admin.compounds.show';

    public const ADMIN_COMPOUNDS_UPDATE = 'admin.compounds.update';

    public const ADMIN_COMPOUNDS_DESTROY = 'admin.compounds.destroy';
    // ==================== Compound Permissions ======================

    // ==================== CompanyInfo Permissions ====================
    public const ADMIN_COMPANY_INFO_SHOW = 'admin.company_info.show';

    public const ADMIN_COMPANY_INFO_UPDATE = 'admin.company_info.update';
    // ==================== CompanyInfo Permissions ====================

    // ==================== Consultant Permissions ======================
    public const ADMIN_CONSULTANTS_INDEX = 'admin.consultants.index';

    public const ADMIN_CONSULTANTS_STORE = 'admin.consultants.store';

    public const ADMIN_CONSULTANTS_SHOW = 'admin.consultants.show';

    public const ADMIN_CONSULTANTS_UPDATE = 'admin.consultants.update';

    public const ADMIN_CONSULTANTS_DESTROY = 'admin.consultants.destroy';
    // ==================== Consultant Permissions ======================

    // ==================== PaymentPlan Permissions ======================
    public const ADMIN_PAYMENT_PLANS_INDEX = 'admin.payment_plans.index';

    public const ADMIN_PAYMENT_PLANS_STORE = 'admin.payment_plans.store';

    public const ADMIN_PAYMENT_PLANS_SHOW = 'admin.payment_plans.show';

    public const ADMIN_PAYMENT_PLANS_UPDATE = 'admin.payment_plans.update';

    public const ADMIN_PAYMENT_PLANS_DESTROY = 'admin.payment_plans.destroy';
    // ==================== PaymentPlan Permissions ======================

    // ==================== Faq Permissions ======================
    public const ADMIN_FAQS_INDEX = 'admin.faqs.index';

    public const ADMIN_FAQS_STORE = 'admin.faqs.store';

    public const ADMIN_FAQS_SHOW = 'admin.faqs.show';

    public const ADMIN_FAQS_UPDATE = 'admin.faqs.update';

    public const ADMIN_FAQS_DESTROY = 'admin.faqs.destroy';
    // ==================== Faq Permissions ======================

    // ==================== Article Permissions ======================
    public const ADMIN_ARTICLES_INDEX = 'admin.articles.index';

    public const ADMIN_ARTICLES_STORE = 'admin.articles.store';

    public const ADMIN_ARTICLES_SHOW = 'admin.articles.show';

    public const ADMIN_ARTICLES_UPDATE = 'admin.articles.update';

    public const ADMIN_ARTICLES_DESTROY = 'admin.articles.destroy';
    // ==================== Article Permissions ======================

    // ==================== Newsletter Permissions ======================
    public const ADMIN_NEWSLETTER_INDEX = 'admin.newsletter.index';

    public const ADMIN_NEWSLETTER_SHOW = 'admin.newsletter.show';

    public const ADMIN_NEWSLETTER_DESTROY = 'admin.newsletter.destroy';
    // ==================== Newsletter Permissions ======================

    // ==================== Page Permissions ======================
    public const ADMIN_PAGES_INDEX = 'admin.pages.index';

    public const ADMIN_PAGES_STORE = 'admin.pages.store';

    public const ADMIN_PAGES_SHOW = 'admin.pages.show';

    public const ADMIN_PAGES_UPDATE = 'admin.pages.update';

    public const ADMIN_PAGES_DESTROY = 'admin.pages.destroy';
    // ==================== Page Permissions ======================

    public static function all(): array
    {
        $reflection = new ReflectionClass(__CLASS__);
        $constants = $reflection->getConstants();

        return array_values($constants);
    }

    public static function exists(string $permission): bool
    {
        return in_array($permission, self::all());
    }
}
