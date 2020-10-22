<?
class TodoController extends BaseController
{
    public function index(Request $request)
    {
                
        $userId = UserModel::getId();
        $taskModel = new TaskModel();
        $statusModel = new StatusModel();

        $nav = [];
        $filter = '';

        $sort = htmlspecialchars($request->get['sort']);
        $dir = 'desc';

        if($sort == ''){
            $sort = 'created_at';
        }

        if($sort == 'title'){
            $dir = 'asc';
        }

        if($request->session['user']['group'] == 'user'){
            $filter = 'user_id = '.$userId;
        }
        
        
        if($request->session['user']['group'] == 'admin'){
            $filter = '';
        }

        if(htmlspecialchars($request->get['view']) == 'list'){

            $count = 2;
            $page = intval($request->get['page']) - 1;
           
           
            if($page < 0) $page = 0;
                        
            $tasks = $taskModel->select('', $filter, $sort.' '.$dir, $count, $page*$count, true)->getArray();
           
            $nav['pageCount'] = intval($taskModel->rowCount / $count);
            $nav['currentPage'] = intval($request->get['page']);

            if($nav['currentPage'] == 0){
                $nav['currentPage'] = 1;
            }

            $view = 'todo-list';
            $statuses = $statusModel->select('', '', 'id asc')->getArrayKey('id');
        }else{
            $tasks = $taskModel->select('', $filter, $sort.' '.$dir)->group('status_id')->getArray();
            $view = 'todo';
            $statuses = $statusModel->select('', '', 'id asc')->getArray();
        }
        
        // if admin we need users info
        if($request->session['user']['group'] == 'admin'){
            
            $userId = null;
            
            if($view == 'todo'){
                $userId = $taskModel->getColumnGrouped('user_id');
            }else{
                $userId = $taskModel->getColumn('user_id');
            }
              
            
            $userModel = new UserModel();
            $users = $userModel->select(['name', 'lastname','email','id'], 'id IN ('.implode(',', $userId).')', 'id desc')->group('id')->getArray();
            
        }
                
        $this->render($view, ['tasks' => $tasks, 'nav' => $nav,  'statuses' => $statuses, 'user' => $request->session['user'], 'users' => $users, 'sort' => $sort], 'Todo');

    }

    public function addOrEdit(Request $request)
    {

        $id = intval($request->post['id']);

        if($id == 0){

            $taskModel = new TaskModel(true);
            $taskModel->title = htmlspecialchars($request->post['title']);
            $taskModel->description	= htmlspecialchars($request->post['description']);
            $taskModel->user_id = UserModel::getId();
            $taskModel->status_id = intval($request->post['status_id']);
            $taskModel->created_at = date('Y-m-d H:i:s');
            $taskModel->important = $request->post['important'];
            $id = $taskModel->save();
            $json = ['id' => $id, 'status' => 'add'];

        }else{

            $taskModel = TaskModel::get($id);
            $result = $taskModel->update(['title' => $request->post['title'], 'description' => $request->post['description']]);
            $json = ['result' => $result, 'status' => 'edit'];
            
        }

        echo json_encode($json);

    }
    
    public function delete(Request $request)
    {
        $json = [];
        $id = intval($request->params[0]);
    
        if($id > 0){
            TaskModel::delete($id);
            $json = ['id' => $id, 'status' => 'deleted'];
        }else{
            $json = ['id' => $id, 'status' => 'fail', 'er' => 'there is no task id'];
        }
        echo json_encode($json);
    }

    public function changeStatus(Request $request)
    {
        $json = [];
        $id = intval($request->get['id']);

        if($id > 0){

            $taskModel = TaskModel::get($id);
            $result = $taskModel->update(['status_id' => $request->get['status_id']]);
            $json = ['result' => $result, 'status' => 'updated'];
            
        }else{
            $json = ['id' => $id, 'status' => 'fail', 'er' => 'there is no task id'];
        }

        echo json_encode($json);
    }
}
?>