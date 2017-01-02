<?php


use Illuminate\Database\Seeder;
use App\Utilities\ShippingContainerTypeUtility;

class ShippingContainerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ShippingContainerType')->insert(
            $this->getShippingContainerTypes()
        );
    }


    private function getShippingContainerTypes ()
    {
        return [

            /**
             * LifeCycle
             */
            [
                'id'    => ShippingContainerTypeUtility::RIGID_BOX,
                'name'  => 'Rigid Box',
            ],
            [
                'id'    => ShippingContainerTypeUtility::BUBBLE_MAILER,
                'name'  => 'Bubble Mailer',
            ],
            [
                'id'    => ShippingContainerTypeUtility::AUTO_BAGGER,
                'name'  => 'Auto Bagger',
            ],

        ];
    }
}
