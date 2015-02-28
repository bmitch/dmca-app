<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Notice;
use Auth;

class NoticesController extends Controller {

    /**
    * Create a new notices controller instance
    **/
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all notices
     */
	public function index()
    {
        return Auth::user()->notices;
    }

    /**
     * Show all notices
     * 
     * @return string
     */
    public function create()
    {
        // get list of providers
        $providers = Provider::lists('name', 'id');
        // load a view to create a new notice
        return view('notices.create', compact('providers'));
    }

    /**
     * Ask the user to confirm the DMCA that will be delivered
     * 
     * @param PrepareNoticeRequest $request
     * @param Guard                $auth
     * @return \Response
     */
    public function confirm(Requests\PrepareNoticeRequest $request, Guard $auth)
    {
        $template = $this->compileDmcaTemplate($data = $request->all(), $auth);
        session()->flash('dmca', $data);
        return view('notices.confirm', compact('template'));
    }

    /**
     *  Store a new DMCA notice.
     *  @param Request $request
     *  @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->createNotice($request);
        return redirect('notices');
    }

    /**
     *  Create and persist a new DMCA notice
     *  @param Request $request
     */
    private function createNotice(Request $request)
    {
        $data = session()->get('dmca');
        $notice = Notice::open($data)
            ->useTemplate($request->input('template'));
        
        Auth::user()->notices()->save($notice);
    }

    public function compileDmcaTemplate($data, Guard $auth)
    {
        $data = $data + [
            'name' => $auth->user()->name,
            'email' => $auth->user()->email,
        ];
        return view()->file(app_path('Http/Templates/dmca.blade.php'), $data);
    }

}
