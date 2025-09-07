<?php

  namespace App\Models;

  use App\Models\AuditLog;
  use Illuminate\Database\Eloquent\Model;
  use Illuminate\Database\Eloquent\Relations\BelongsTo;
  use Illuminate\Database\Eloquent\Factories\HasFactory;


  class Bill extends Model
  {
               use HasFactory;



      protected $fillable = [
          'patient_id', 'department_id', 'amount', 'bill_date', 'description',
          'is_anomaly', 'insurance_provider', 'insurance_coverage', 'payment_status', 'user_id'
      ];

      protected $casts = [
          'is_anomaly' => 'boolean',
          'amount' => 'float', 
          'insurance_coverage' => 'float', 
          'bill_date' => 'date',
      ];

      protected static function booted()
      {
          static::created(function ($model) {
              AuditLog::create([
                  'action' => 'created',
                  'model_type' => get_class($model),
                  'model_id' => $model->id,
                  'user_id' => auth()->id() ?? 1,
                  'changes' => json_encode($model->getAttributes()),
              ]);
          });

          static::updated(function ($model) {
              AuditLog::create([
                  'action' => 'updated',
                  'model_type' => get_class($model),
                  'model_id' => $model->id,
                  'user_id' => auth()->id() ?? 1,
                  'changes' => json_encode($model->getChanges()),
              ]);
          });

          static::deleted(function ($model) {
              AuditLog::create([
                  'action' => 'deleted',
                  'model_type' => get_class($model),
                  'model_id' => $model->id,
                  'user_id' => auth()->id() ?? 1,
                  'changes' => null,
              ]);
          });
      }

      public function patient(): BelongsTo
      {
          return $this->belongsTo(Patient::class, 'patient_id');
      }

      public function department(): BelongsTo
      {
          return $this->belongsTo(Department::class, 'department_id');
      }

      public function user(): BelongsTo
      {
          return $this->belongsTo(Employee::class, 'user_id');
      }
  }