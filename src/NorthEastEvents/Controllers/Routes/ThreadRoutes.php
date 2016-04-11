<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 10/04/2016
 * Time: 19:22
 */

namespace NorthEastEvents\Controllers\Routes;


class ThreadRoutes extends Routes {

    public function routes() {
        $app = $this->app;

        /**
         * Front-end Controllers
         */



        /**
         * API Controllers
         */

        $app->group('/api', function () {
            // Get the last x threads created
            $this->get('/threads', '\NorthEastEvents\ThreadController:APIGetThreads')
                ->setName("APIThreadsList");

            // Specific thread operations
            $this->group('/thread',function(){
                // Create thread
                $this->post('', '\NorthEastEvents\ThreadController:APICreateThread')
                    ->setName("APIEventThreadCreate");

                // Specific thread operations
                $this->group('/{threadID:[0-9]+}',function(){
                    // Get/delete/put/patch specific event
                    $this->map(["GET", "DELETE", "PUT", "PATCH"], '', '\NorthEastEvents\ThreadController:APIEventThreadHandler')
                        ->setName("APIThreadOperations");

                    // Get all comments of a thread
                    $this->get('/comments', '\NorthEastEvents\ThreadController:APIGetThreadComments')
                        ->setName("APIThreadCommentsList");
                });
            });
        });
    }
}