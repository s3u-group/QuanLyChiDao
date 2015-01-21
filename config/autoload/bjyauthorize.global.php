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
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
                'menu' => array(),
            ),
        ),
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    
                   /* [0] -> role
                    [1] -> resource
                    [2] -> rule*/
                    
                    array( array( 'quan-tri', 'xu-ly-cong-viec' ), 'menu', array( 'menu_xu_ly_viec' ) ),
                    array( array( 'quan-tri', 'giao-viec' ), 'menu', array( 'menu_giao_viec' ) ),
                    array( array( 'quan-tri', 'ket-xuat' ), 'menu', array( 'menu_ket_xuat' ) ),
                    array( array( 'quan-tri' ), 'menu', array( 'menu_quan_tri' ) ),
                ),
            ),
        )
    ),
);
