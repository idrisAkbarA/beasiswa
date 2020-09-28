<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UserImport implements ToCollection, WithHeadingRow

{
    use Importable;
    /**
     * @param collection $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nama = $row['nama'];
            $nim = $row['nim'];
            $password = $row['password'];
            $email = $row['email'];

            // if (!isset($jumlah)) {
            //     return null;
            // }

            User::create(
                [
                    'nama'  => $nama,
                    'nim'  => $nim,
                    'password'  => $password,
                    'email'  => $email,
                ]
            );
        }
    }
}
