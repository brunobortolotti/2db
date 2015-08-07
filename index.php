<?
header("Content-Type: text/html; charset=UTF-8",true);

require_once('2db.php');

$Database = new ToDB();

$Database->setup(server, username, password, schema, charset);

$query = $Database->select()
			->table('user')
			->innerjoin('user_group', [['user.user_group_id','user_group.id'], ['user.id','<>', 'user_group.id']])
			->leftjoin('user_info', [['user_info.user_id','user.id']])
			->where('user_group.label','diretor administrativo')
			->orWhere('user_group.label','diretor comercial')
			->dump();

var_dump($query);

$query = $Database->update()
			->table('user')
			->field('password', md5('123456'))
			->where('id', 20)
			->dump();

var_dump($query);

$query = $Database->insert()
			->table('user_group')
			->field('key', md5(rand().date('YmdHis')))
			->field('enterprise_id', 3)
			->field('label', 'Teste')
			->field('deleted', false)
			->dump();

var_dump($query);

$query = $Database->delete()
			->table('user')
			->where('id',1)
			->orWhere('id',2)
			->dump();

var_dump($query);

?>