<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\MessageModel;
use App\Models\UserModel;

class MessageController extends BaseController {

    public function messages() {

        $sessionUserID = session()->get('id_user');

        if(!$sessionUserID) {
            return redirect()->to('/login');
        }

        $MessageModel = new MessageModel();
        $data['Messages'] = $MessageModel->getMessages($sessionUserID);
        $UserModel = new UserModel();
        $data['Agents'] =   $UserModel->getAgentsAndAffiliation();
        return view('/useragent/message', $data);
    }

    public function messageLanding() {
        $sessionUserID = session()->get('id_user');
        $receiver = $sessionUserID;
        $sender = $this->request->getVar('sender'); 
        $senderfname = $this->request->getVar('senderfname'); 
        $senderlname = $this->request->getVar('senderlname'); 
       
        if ($sender) {
            $MessageModel = new MessageModel();
            $data['Convo'] = $MessageModel->getConvo($receiver, $sender);
            $data['sender'] = $sender;
            $data['senderfname'] = $senderfname;
            $data['senderlname'] = $senderlname;

            return view('/useragent/messagelanding', $data);
        }
    }

    public function sendMessage() {

        $sessionUserID = session()->get('id_user');

        if ($this->request->getMethod() == 'POST') {
            
            $sender = $sessionUserID;
            $receiver = $this->request->getPost('receiverID');
            $message = $this->request->getPost('messageToSend');
            
            $data = [
                'sender' => $sender,
                'receiver' => $receiver,
                'messagecontent' => $message,
            ];

            $MessageModel = new MessageModel();
            $inserted = $MessageModel->insert($data);

            if ($inserted) {
                return $this->response->setJSON(['success' => true, 'message' => 'Message sent successfully']);
            } 
            else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to send message']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Invalid request method']);
    }


    public function fetchMessagesAjax() {
        $receiver = session()->get('id_user');
        $sender = $this->request->getGet('sender');

        if ($sender) {
            $MessageModel = new MessageModel();
            $messages = $MessageModel->getConvo($receiver, $sender);
            return $this->response->setJSON($messages);
        }

        return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing sender']);
    }
}
