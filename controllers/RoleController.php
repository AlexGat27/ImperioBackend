<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RoleController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['roles'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $rolesWithChildren = [];
        foreach ($roles as $role) {
            $children = $auth->getChildren($role->name);
            $rolesWithChildren[] = [
                'role' => $role,
                'children' => $children,
            ];
        }

        return $rolesWithChildren;
    }

    public function actionView($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        if ($role !== null) {
            return [
                'role' => $role,
                'children' => $auth->getChildren($role->name),
            ];
        }

        throw new NotFoundHttpException('Role not found');
    }

    public function actionCreate()
    {
        $auth = Yii::$app->authManager;
        $roleName = Yii::$app->request->post("role_name");

        if ($roleName && !$auth->getRole($roleName)) {
            $role = $auth->createRole($roleName);
            $role->description = Yii::$app->request->post("description");
            $auth->add($role);

            $children = Yii::$app->request->post("children", []);
            foreach ($children as $childRoleName) {
                $childRole = $auth->getRole($childRoleName);
                if ($childRole) {
                    $auth->addChild($role, $childRole);
                }
            }
            return [
                'role' => $role,
                'children' => $children,
            ];
        }

        return ["message" => "Cannot create role. Role name already exists or invalid."];
    }

    public function actionUpdate($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        if ($role) {
            $role->description = Yii::$app->request->post("description");
            $auth->update($name, $role);
            return $role;
        }

        return ["message" => "Cannot update role. Role not found."];
    }

    public function actionDelete($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        if ($role) {
            $auth->remove($role);
            return ["message" => "Successfully deleted role"];
        }

        return ["message" => "Role not found"];
    }
}
