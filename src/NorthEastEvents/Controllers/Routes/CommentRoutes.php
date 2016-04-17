<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 10/04/2016
 * Time: 19:16
 */

namespace NorthEastEvents\Controllers\Routes;


class CommentRoutes extends Routes {
    public function routes() {
        $app = $this->app;

        $app->group('/comment',function(){
            // Create comment on event thread
            $this->post('', '\NorthEastEvents\Controllers\CommentController:APICreateComment')
                ->setName("ThreadCommentCreate");

            // Specific comment operations
            $this->group('/{commentID:[0-9]+}',function(){
                // Get/delete/put/patch specific comment
                $this->map(["GET", "DELETE", "PUT", "PATCH"], '', '\NorthEastEvents\Controllers\CommentController:APICommentHandler')
                    ->setName("APIThreadCommentOperations");
            });
        });

        /**
         * API Controllers
         */

        $app->group('/api', function() {
            $this->group('/comment',function(){
                // Create comment on event thread
                $this->post('', '\NorthEastEvents\Controllers\CommentController:APICreateComment')
                    ->setName("APIThreadCommentCreate");

                // Specific comment operations
                $this->group('/{commentID:[0-9]+}',function(){
                    // Get/delete/put/patch specific comment
                    $this->map(["GET", "DELETE", "PUT", "PATCH"], '', '\NorthEastEvents\Controllers\CommentController:APICommentHandler')
                        ->setName("APIThreadCommentOperations");
                });
            });
        });
    }
}