<?php

namespace Database\Seeders;

use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\Salesman;
use App\Models\Title;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SalesmanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Exception
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();
            $this->processCsv();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function processCsv(): void
    {
        $csvFile = fopen("database/seed_data/salesmen.csv", "r");

        //read the first line
        fgetcsv($csvFile);

        $genders = Gender::all();
        $genderMap = [];
        foreach ($genders as $gender) {
            $genderMap[$gender->code] = $gender->id;
        }

        $maritalStatuses = MaritalStatus::all();
        $msMap = [];
        foreach ($maritalStatuses as $ms) {
            $msMap[$ms->code] = $ms->id;
        }

        $titles = Title::all();
        $tbnMap = [];
        $tanMap = [];
        foreach ($titles as $title) {
            if ($title->type == Title::TYPE_BEFORE) {
                $tbnMap[$title->name] = $title->id;
            } else if ($title->type == Title::TYPE_AFTER) {
                $tanMap[$title->name] = $title->id;
            }
        }

        while ($data = fgetcsv($csvFile, null, ";")) {
            $genderCode = $data[7];
            if (!isset($genderMap[$genderCode])) {
                throw new \Exception("Gender $genderCode not found");
            }

            $msCode = $data[8];
            if (!empty($msCode) && !isset($msMap[$msCode])) {
                throw new \Exception("Marital status $msCode not found");
            }

            $titles_before = (empty($data[2])) ? [] : explode(",", $data[2]);
            $titles_after = (empty($data[3])) ? [] : explode(",", $data[3]);

            $sm = Salesman::create([
                'first_name' => $data[0],
                'last_name' => $data[1],
                'prosight_id' => $data[4],
                'email' => $data[5],
                'phone' => $data[6],
                'gender_id' => $genderMap[$genderCode],
                'marital_status_id' => (empty($msCode)) ? null : $msMap[$msCode],
            ]);

            foreach ($titles_before as $tb) {
                if (!isset($tbnMap[$tb])) {
                    throw new \Exception("Title before name $tb not found");
                }
                $sm->titles()->attach($tbnMap[$tb]);
            }

            foreach ($titles_after as $ta) {
                if (!isset($tanMap[$ta])) {
                    throw new \Exception("Title after name $ta not found");
                }
                $sm->titles()->attach($tanMap[$ta]);
            }
            $sm->save();
        }

        fclose($csvFile);
    }
}
