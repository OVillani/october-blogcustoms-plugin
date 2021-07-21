<?php 

namespace Synder\BlogCustoms\Models;

use Model;
use Illuminate\Support\Facades\Validator;
use RainLab\Blog\Models\Post;


class Custom extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * Database Table
     *
     * @var string
     */
    public $table = 'synder_blogcustoms';

    /**
     * Database Relations
     *
     * @var array
     */
    public $belongsTo = [
        'post' => [
            Post::class,
            'table' => 'rainlab_blog_posts'
        ]
    ];

    /**
     * Fillable fields
     *
     * @var array
     */
    public $fillable = [
        'post_id',
        'name',
        'type',
        'value'
    ];

    /**
     * Field Rules
     *
     * @var array
     */
    public $rules = [
        'post_id' => [
            'required',
            'exists:RainLab\Blog\Models\Post,id'
        ],
        'name' => [
            'required',
            'regex:/^[a-z0-9_]+$/',
            'not_regex:/^[0-9]/'
        ],
        'type' => [
            'required',
            'in:text,number,array'
        ],
        'value' => [
            'nullable'
        ]
    ];

    
    /**
     * Store Custom Datasets from array
     *
     * @param array $array
     * @param Post $model
     * @return void
     */
    static public function storeFromArray(array $array, Post $model)
    {
        $customs = static::where('post_id', $model->id)->get();
        $customindex = array_column($customs->toArray(), 'name', 'id');

        // Add & Update Existing DataSets
        foreach ($array AS $key => $set) {
            if (is_string($key)) {
                if (strpos($key, '_') === 0) {
                    $key = null;
                } else {
                    $key = (int) $key;
                }
            }

            if ($key !== null) {
                $custom = $customs->find($key);
                unset($customindex[$key]);
            } else {
                $custom = new static();
                $custom->post_id = $model->id;
            }

            $custom->name = $set['name'];
            $custom->type = $set['type'];
            $custom->value = $set['value'];
            $custom->save();
        }

        // Delete removed DataSets
        foreach ($customindex AS $key => $name) {
            $custom = $customs->find($key);
            $custom->delete();
        }
    }
}