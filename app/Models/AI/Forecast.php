<?php
namespace App\Models\AI;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Forecast extends Model
{
    use HasFactory;

    protected $table = 'ai_forecasts';
    protected $fillable = ['organization_id', 'metric', 'predictions', 'confidence', 'forecast_date', 'forecast_until'];

    protected $casts = [
        'predictions' => 'array',
        'forecast_date' => 'date',
        'forecast_until' => 'date'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}