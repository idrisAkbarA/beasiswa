<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
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
            $jurusan_id = $row['jurusan_id'];
            $password = $row['password'];
            $email = $row['email'];

            User::create(
                [
                    'nama'  => $nama,
                    'nim'  => $nim,
                    'jurusan_id'  => $jurusan_id,
                    'password'  => Hash::make($password),
                    'email'  => $email,
                ]
            );
        }
    }
}
