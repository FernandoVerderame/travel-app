<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'start_date', 'end_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function days()
    {
        return $this->hasMany(Day::class);
    }

    public function getFormattedDate($column, $format = 'd-m-Y')
    {
        return Carbon::create($this->$column)->format($format);
    }

    public function printImage()
    {
        return asset('storage/' . $this->image);
    }

    public function generateDays($start_date, $end_date, $has_days = false)
    {
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        if (!$has_days) {
            // Crea nuovi giorni tra le date
            $this->createDays($start_date, $end_date);
        } else {
            // Gestisci i giorni esistenti e i nuovi giorni
            $this->updateDays($start_date, $end_date);
        }
    }

    /**
     * Crea nuovi giorni tra due date.
     */
    private function createDays($start_date, $end_date)
    {
        $days = $start_date->diffInDays($end_date) + 1; // +1 per includere anche il giorno di ritorno

        for ($i = 0; $i < $days; $i++) {
            $date = $start_date->copy()->addDays($i);

            Day::updateOrCreate(
                ['trip_id' => $this->id, 'date' => $date],
                ['number' => $i + 1]
            );
        }
    }

    /**
     * Aggiorna i giorni esistenti e aggiunge nuovi giorni se necessario.
     */
    private function updateDays($start_date, $end_date)
    {

        // Assicurati che start_date e end_date siano oggetti Carbon
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        // Elimina giorni non necessari
        Day::where('trip_id', $this->id)
            ->where(function ($query) use ($start_date, $end_date) {
                $query->where('date', '<', $start_date)
                    ->orWhere('date', '>', $end_date);
            })
            ->delete();

        // Crea nuovi giorni se necessario
        if ($start_date < $this->start_date) {
            // Nuova partenza precedente
            $this->createDays($start_date, $this->start_date->copy()->subDays(1));
        }

        if ($end_date > $this->end_date) {
            // Nuovo ritorno successivo
            $this->createDays($this->end_date->copy()->addDays(1), $end_date);
        }
    }
}
