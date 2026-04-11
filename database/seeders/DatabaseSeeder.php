<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([UserRoleSeeder::class]);
        $this->call([PermissionSeeder::class]);
        $this->call(UnitSeeder::class);
        $this->call([AttributesSeeder::class]);
        $this->call([AttributeOptionSeeder::class]);
        $this->call([CountrySeeder::class]);
        $this->call([StateSeeder::class]);
        $this->call([CitySeeder::class]);
        $this->call([PropertyTypeSeeder::class]);
        $this->call([AttributePropertyTypeSeeder::class]);
        $this->call([DeveloperSeeder::class]);
        $this->call([PropertySeeder::class]);
        $this->call([CompoundSeeder::class]);
        $this->call([DiscountSeeder::class]);
        $this->call([FavoriteSeeder::class]);
        $this->call([PropertyFavoriteSeeder::class]);
        $this->call([SellUnitSeeder::class]);
        $this->call([CompanyInfoSeeder::class]);
        $this->call([SocialSeeder::class]);
        $this->call([AssignAttributesToPropertiesSeeder::class]);
        $this->call([CompoundMediaSeeder::class]);
        $this->call([OfferSeeder::class]);
        $this->call([BannerSeeder::class]);
        $this->call([FeaturedSeeder::class]);
        $this->call([ConsultantSeeder::class]);
        $this->call([ConsultantReviewSeeder::class]);
        $this->call([PropertyMediaSeeder::class]);
        $this->call([PaymentPlanSeeder::class]);

    }
}
