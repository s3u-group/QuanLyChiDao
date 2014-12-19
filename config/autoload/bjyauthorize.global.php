<?php
return array(
    'bjyauthorize' => array(
        // default role for unauthenticated users
        'default_role'          => 'khach',

        'identity_provider'  => '\BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',
    //    'identity_provider'     => '\BjyAuthorize\Provider\Identity\ZfcUserZendDb',

        'role_providers' => array(
        
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'role_entity_class' => 'User\Entity\Role',
                'object_manager'    => 'doctrine.entitymanager.orm_default',
            ),
        ),
        
    ),
);
