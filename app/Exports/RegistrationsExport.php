<?php

namespace App\Exports;

use App\Models\Registration;
use App\Queries\Admin\RegistrationQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegistrationsExport implements FromCollection, WithHeadings, WithMapping
{
    protected array $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Gunakan RegistrationQuery untuk mendapatkan data yang sudah difilter
        return app(RegistrationQuery::class)->getFiltered($this->filters);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Registrasi',
            'Nama Event',
            'ID Peserta',
            'Nama Peserta',
            'Email Peserta',
            'No. Telepon Peserta',
            'Jenis Tiket',
            'Jumlah Tiket',
            'Total Bayar',
            'Status',
            'Tanggal Registrasi',
        ];
    }

    /**
     * @param Registration $registration
     * @return array
     */
    public function map($registration): array
    {
        return [
            $registration->id,
            $registration->event->title,
            $registration->user->id,
            $registration->user->name,
            $registration->user->email,
            $registration->user->phone_number,
            $registration->ticket->name,
            $registration->jumlah_tiket,
            $registration->total_bayar,
            ucfirst(str_replace('_', ' ', $registration->status)),
            $registration->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
