<?php

declare(strict_types=1);

namespace app\models\forms;

use app\models\entities\Book;
use app\models\entities\Author;
use Yii;
use app\services\BookImageServiceInterface;

/**
 *
 */
class BookCreateForm extends \yii\base\Model
{
    public const AUTHORS_MAX_AMOUNT = 3;

    public string $name = '';

    public ?string $description = '';

    public ?string $isbn = '';

    public int $year = 0;

    public $imageFile;

    public $imagePath = '';

    public int  $id_user;

    public $authors = [];

    private BookImageServiceInterface $imgService;

    public function __construct(BookImageServiceInterface $imgService, $config = [])
    {
        parent::__construct($config);
        $this->imgService = $imgService;
    }

    public function init()
    {
        $this->id_user = Yii::$app->user->identity->id;
        $this->year = (int) date("Y");

        for ($i = 1; $i <= self::AUTHORS_MAX_AMOUNT; $i++) {
            $this->authors['author' . $i] = '';
        }

        parent::init();
    }

    public function attributes()
    {
        $attrs =  parent::attributes();
        foreach ($this->authors as $key => $val) {
            $attrs[] = $key;
        }
        return $attrs;
    }

    public function __get($name)
    {
        if (str_starts_with($name, 'author')) {
            if (isset($this->authors[$name])) {
                return $this->authors[$name];
            }
        }
        return parent::__get($name);
    }


    public function __set($name, $value)
    {
        if (str_starts_with($name, 'author')) {
            if (isset($this->authors[$name])) {
                $this->authors[$name] = $value;
                return $this->authors[$name];
            }
        }
        return parent::__set($name, $value);
    }

    public function rules()
    {
        $res = [
            [['description', 'isbn'], 'default', 'value' => null],
            [['name'], 'required'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 100],
            [['isbn'], 'string', 'max' => 17],
            [['year'], 'match', 'pattern' => '/^\d{4}$/'],
            [['year'], 'number', 'min' => 1900, 'max' => intval(date('Y')) + 1],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            'isbn' => [['isbn'], 'unique', 'targetClass' => Book::class],
            [['author1'], 'checkAuthors', 'skipOnEmpty' => false],
        ];

        foreach ($this->authors as $key => $val) {
            $res[] = [[$key], 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'];
        }

        return $res;
    }

    public function checkAuthors($attribute, $params, $validator)
    {
        $existed = [];
        $hasAuthor = false;
        foreach ($this->authors as $author) {
            if ($author) {
                $hasAuthor = true;
                if (isset($existed[$author])) {
                    $this->addError($attribute, 'Авторы не должны повторяться');
                } else {
                    $existed[$author] = true;
                }

            }
        }
        if (!$hasAuthor) {
            $this->addError($attribute, 'Вы должны указать хотя бы одного автора');
        }
    }

    public function upload(): void
    {
        if ($this->imageFile) {
            $this->imagePath = $this->imgService->save($this->imageFile);
        }

    }

    public function getAuthorIds(): array
    {
        return array_map('intval', array_filter(array_values($this->authors)));
    }
}
