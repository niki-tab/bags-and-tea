<?php
namespace Src\Authorization\Users\Domain;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Src\Metadata\Infrastructure\Eloquent\MetadataModel;
class UserMetaModel extends Model
{
    use HasFactory;
    protected $table = 'user_metas';
    protected $primaryKey = 'id';
    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
    ];
    protected $fillable = [
        "user_id",
        "phone_number",
    ];
    
    public function user()
    {
        return $this->hasOne(UserMetaModel::class, 'user_id', 'id');
        
    }
}