<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/5/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\View\Exception\MissingTemplateException When the view file could not
     *   be found and in debug mode.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found and not in debug mode.
     * @throws \Cake\View\Exception\MissingTemplateException In debug mode.
     */
    public function display(string ...$path): ?Response
    {
        if (!$path) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        if (!empty($path[0])) {
            $page = $path[0];
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        $this->set(compact('page', 'subpage'));

        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }

    public function about() {
    }

    public function regist()
    {
        // Load the SecretNotes model
        $secretNote = $this->getTableLocator()->get('SecretNote')->newEmptyEntity();

        if ($this->request->is('post')) {
            // Get form data
            $data = $this->request->getData();

            // Check if passwords match
            if ($data['password'] !== $data['confirm_password']) {
                $this->Flash->error(__('Passwords do not match.'));
            } else {
                // Patch the form data into the entity
                $secretNote = $this->getTableLocator()->get('SecretNote')->patchEntity($secretNote, $data);

                // Set the default values for new users
                $secretNote->update_token = 0;
                $secretNote->note = "Write your first note here!";

                // Check for validation errors
                if ($secretNote->getErrors()) {
                    $this->Flash->error(__('Please choose another username.'));
                } else {
                    // Save the entity
                    if ($this->getTableLocator()->get('SecretNote')->save($secretNote)) {
                        $this->Flash->success(__('Your note has been saved.'));
                        return $this->redirect(['action' => 'login']); // Redirect after success
                    } else {
                        $this->Flash->error(__('There was an error saving the note.'));
                    }
                }
            }
        }

        // Use the correct variable name in compact
        $this->set(compact('secretNote'));
    }


    public function login()
    {   
        if ($this->request->is('post')) {
            $username = $this->request->getData('username');
            $password = $this->request->getData('password');
            
            // Load the SecretNote table if it's not already loaded
            $secretNoteTable = $this->getTableLocator()->get('SecretNote');
            
            // Find the record by username (you can add more conditions if necessary)
            $user = $secretNoteTable->find()
                ->where(['username' => $username])
                ->first(); // Only take the first matching record
            
            // Check if a user with that username exists
            if ($user) {
                // Verify the password (Use password_verify for security)
                if (password_verify($password, $user->password)) {
                    // Password matches, log the user in
                    $this->Flash->success('Login successful');

                    // Store the user in the session
                    $this->Authentication->setIdentity($user);

                    // Redirect to the homepage
                    return $this->redirect('/home');
                } else {
                    // Password does not match
                    $this->Flash->error('Invalid password');
                }
            } else {
                // No user found
                $this->Flash->error('Invalid username');
            }
        }
    }

    public function home()
    {
        // Retrieve the logged-in user
        $user = $this->Authentication->getIdentity();

        // Check if the user is authenticated
        if ($user) {
            // Get the SecretNote table using the TableLocator
            $secretNoteTable = $this->getTableLocator()->get('SecretNote');
            
            // Query the SecretNote table by the logged-in username
            $secretNote = $secretNoteTable->find()
                ->where(['username' => $user->username])
                ->first(); // Get the first result (the note for that user)

            // Check if we found a note
            if (!$secretNote) {
                $this->Flash->error('No note found.');
            }
        } else {
            // User not authenticated, redirect to login page
            return $this->redirect(['controller' => 'Pages', 'action' => 'login']);
        }

        // Always pass the correct variable to the view
        $this->set(compact('secretNote'));
    }

    public function saveNote()
    {
        // Check if the request is POST
        if ($this->request->is('post')) {
            // Retrieve the logged-in user
            $user = $this->Authentication->getIdentity();

            if ($user) {
                // Load the SecretNote model
                $secretNoteTable = $this->getTableLocator()->get('SecretNote');

                // Find the user's existing note or create a new entity
                $secretNote = $secretNoteTable->find()
                    ->where(['username' => $user->username])
                    ->first();

                if (!$secretNote) {
                    $secretNote = $secretNoteTable->newEmptyEntity();
                    $secretNote->update_token = 0; // Initialize update_token for a new note
                } else {
                    // Increment the update_token for existing notes
                    $secretNote->update_token = $secretNote->update_token + 1;
                }

                // Patch data into the entity
                $secretNote = $secretNoteTable->patchEntity($secretNote, $this->request->getData());

                // Save the note
                if ($secretNoteTable->save($secretNote)) {
                    $this->Flash->success(__('Your note has been saved.'));
                } else {
                    $this->Flash->error(__('Unable to save your note. Please try again.'));
                }
            } else {
                $this->Flash->error(__('You must be logged in to save notes.'));
            }
        }

        // Redirect back to the home page or a relevant page
        return $this->redirect(['action' => 'home']);
    }

    public function logoutUser() {
        $result = $this->Authentication->getResult();
        // regardless of POST or GET, redirect if user is logged in
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            return $this->redirect(['controller' => 'Pages', 'action' => 'login']);
        }
    }

    public function deleteUser()
    {
        // Check if the user is authenticated
        $user = $this->Authentication->getIdentity();

        if ($user) {
            // Load the SecretNote model
            $secretNoteTable = $this->getTableLocator()->get('SecretNote');

            // Find the user's note by username
            $secretNote = $secretNoteTable->find()
                ->where(['username' => $user->username])
                ->first();

            if ($secretNote) {
                // Delete the user's note
                if ($secretNoteTable->delete($secretNote)) {
                    $this->Flash->success(__('Your account and notes have been successfully deleted.'));

                    // Log out the user after deleting the account
                    $this->Authentication->logout();

                    // Redirect to the home page or login page
                    return $this->redirect(['controller' => 'Pages', 'action' => 'login']);
                } else {
                    $this->Flash->error(__('Unable to delete your account. Please try again.'));
                }
            } else {
                $this->Flash->error(__('No account found to delete.'));
            }
        } else {
            $this->Flash->error(__('You must be logged in to delete your account.'));
            return $this->redirect(['controller' => 'Pages', 'action' => 'login']);
        }
    }

}
