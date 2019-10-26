<?php namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{

	protected $table      = 'users';
	protected $primaryKey = 'user_id';

	protected $returnType     = 'array';
	protected $useSoftDeletes = true;

	protected $allowedFields = [
		'user_email',
		'user_password',
	];

	protected $useTimestamps = true;
	protected $createdField  = 'user_created';
	protected $updatedField  = 'user_modified';
	protected $deletedField  = 'user_deleted';

	protected $validationRules    = [];
	protected $validationMessages = [];
	protected $skipValidation     = true;

}
