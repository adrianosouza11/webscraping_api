<?php


namespace App\Models;

use App\Exceptions\InvalidArgApiException;
use Illuminate\Contracts\Validation\Validator as ValidatorContracts;
use \Illuminate\Support\Facades\Validator;
use \Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    public $timestamps = false;

    protected $table = 'products';

    protected $fillable = [
      'title',
      'description',
      'price',
      'image_base64',
      'url_origin',
      'is_discontinued',
      'datetime_created'
    ];

    public $errors = array();

    public static function rules() : array
    {
        return [
          'title' => 'required|string|max:355',
          'description' => 'required|string',
          'price' => 'required|numeric',
          'image_base64' => 'required|string',
          'url_origin' => 'required|string',
          'is_discontinued' => 'nullable|boolean',
          'datetime_created' => 'required|date|date_format:Y-m-d H:i:s'
        ];
    }

    public function validator() : ValidatorContracts
    {
        return Validator::make($this->getAttributes(), self::rules(), [
            'required' => 'O campo ":attribute" é obrigatório.',
        ]);
    }

    public function actionValidate() : void
    {
        if ($this->validator()->fails())
        {
            $this->errors = $this->validator()->errors()->all();

            throw new InvalidArgApiException("Invalid Params in Products");
        }
    }

    public function create(array $dataSet) : void
    {
        $this->setRawAttributes($dataSet);

        $this->actionValidate();

        $this->saveOrFail();
    }
}