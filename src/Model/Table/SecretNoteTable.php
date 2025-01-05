<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SecretNote Model
 *
 * @method \App\Model\Entity\SecretNote newEmptyEntity()
 * @method \App\Model\Entity\SecretNote newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\SecretNote> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SecretNote get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\SecretNote findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\SecretNote patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\SecretNote> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\SecretNote|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\SecretNote saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\SecretNote>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SecretNote>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SecretNote>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SecretNote> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SecretNote>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SecretNote>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\SecretNote>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\SecretNote> deleteManyOrFail(iterable $entities, array $options = [])
 */
class SecretNoteTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('secret_note');
        $this->setDisplayField('username');
        $this->setPrimaryKey('id');
    }
    
    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('username')
            ->maxLength('username', 255)
            ->requirePresence('username', 'create')
            ->notEmptyString('username')
            ->add('username', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'This username is already taken.']);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'Please provide a password.');

        $validator
            ->scalar('note')
            ->allowEmptyString('note');

        $validator
            ->dateTime('create_time')
            ->notEmptyDateTime('create_time');

        $validator
            ->dateTime('update_time')
            ->notEmptyDateTime('update_time');

        $validator
            ->integer('update_token')
            ->requirePresence('update_token', 'create')
            ->notEmptyString('update_token');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['username']), ['errorField' => 'username']);

        return $rules;
    }
}
