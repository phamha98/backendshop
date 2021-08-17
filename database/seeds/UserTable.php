<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\DB;

class UserTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        $faker=Faker\Factory::create();
        for($i=0;$i<10;$i++){
            $user=new User;
            $user->name=$faker->name;
            $user->email=$faker->unique()->email;
            $user->password= bcrypt($faker->text);//comment 26/05
            $user->phone=$faker->phoneNumber;
            $user->gender=$faker->randomElement($array = array ('nam','nu'));
            $user->birthday=$faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now');
            $user->address=$faker->address;
            $user->img=$faker->imageUrl($width = 640, $height = 480);
            $user->save();

            DB::table("role_user")->insert([
                "id_user"=>$user->id,
                "id_role"=>"4"
            ]);       
        } 

    }
}
