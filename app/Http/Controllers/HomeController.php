<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Plan;
use App\Project;
use App\ProjectTask;
use App\Timesheet;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    use \RachidLaasri\LaravelInstaller\Helpers\MigrationsHelper;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();


        if ($user->type == 'admin') {
            return view('admin.dashboard');
        } else {
            $home_data = [];

            $user_projects = $user->projects()->pluck('project_id')->toArray();


            $project_tasks = ProjectTask::whereIn('project_id', $user_projects)->get();
            $project_expense = Expense::whereIn('project_id', $user_projects)->get();
            $seven_days = Utility::getLastSevenDays();


            // Total Projects
            $complete_project = $user->projects()->where('status', 'LIKE', 'complete')->count();
            $home_data['total_project'] = [
                'total' => count($user_projects),
                'percentage' => Utility::getPercentage($complete_project, count($user_projects)),
            ];


            // Total Tasks

            //$complete_task = ProjectTask::where('is_complete', '=', 1)->whereRaw("find_in_set('" . $user->id . "',assign_to)")->whereIn('project_id', $user_projects)->count();
            $complete_task = 0;
            $home_data['total_task'] = [
                'total' => 0,
                'percentage' => 0
                    //Utility::getPercentage($complete_task, $project_tasks->count()),
            ];

            // Total Expense
            $total_expense = 0;
            $total_project_amount = 0;
            foreach ($user->projects as $pr) {
                $total_project_amount += $pr->budget;
            }
            foreach ($project_expense as $expense) {
                $total_expense += $expense->amount;
            }
            $home_data['total_expense'] = [
                'total' => $project_expense->count(),
                'percentage' => Utility::getPercentage($total_expense, $total_project_amount),
            ];

            // Total Users
            // $home_data['total_user'] = User::where('created_by', '=', Auth::user()->id)->count();
            $home_data['total_user'] = Auth::user()->contacts->count();

            // Tasks Overview Chart & Timesheet Log Chart
            $task_overview = [];
            $timesheet_logged = [];
            foreach ($seven_days as $date => $day) {
                // Task
                $task_overview[__($day)] = ProjectTask::where('is_complete', '=', 1)->where('marked_at', 'LIKE', $date)->whereIn('project_id', $user_projects)->count();

                // Timesheet
                $time = Timesheet::whereIn('project_id', $user_projects)->where('date', 'LIKE', $date)->pluck('time')->toArray();
                $timesheet_logged[__($day)] = str_replace(':', '.', Utility::calculateTimesheetHours($time));
            }

            $home_data['task_overview'] = $task_overview;
            $home_data['timesheet_logged'] = $timesheet_logged;

            // Project Status
            $total_project = count($user_projects);
            $project_status = [];
            foreach (Project::$status as $k => $v) {
                $project_status[$k]['total'] = $user->projects->where('status', 'LIKE', $k)->count();
                $project_status[$k]['percentage'] = Utility::getPercentage($project_status[$k]['total'], $total_project);
            }
            $home_data['project_status'] = $project_status;

            // Top Due Project
            $home_data['due_project'] = $user->projects()->orderBy('end_date', 'DESC')->limit(5)->get();

            // Top Due Tasks
            $home_data['due_tasks'] = ProjectTask::where('is_complete', '=', 0)->whereIn('project_id', $user_projects)->orderBy('end_date', 'DESC')->limit(5)->get();

            $home_data['last_tasks'] = ProjectTask::whereIn('project_id', $user_projects)->orderBy('end_date', 'DESC')->limit(5)->get();


            return view('admin.dashboard', compact('home_data'));
        }
    }

    public function check()
    {
        $user = \Auth::user();

        if ($user->type != 'admin') {
            $plan = Plan::find($user->plan);
            if ($plan) {
                if ($plan->duration != 'Unlimited') {
                    $datetime1 = new \DateTime($user->plan_expire_date);
                    $datetime2 = new \DateTime(date('Y-m-d'));
                    //                    $interval  = $datetime1->diff($datetime2);
                    $interval = $datetime2->diff($datetime1);
                    $days = $interval->format('%r%a');
                    if ($days <= 0) {
                        $user->assignPlan(1);

                        return redirect()->route('home')->with('error', __('Your Plan is expired.'));
                    }
                }
            } else {
                return redirect()->route('home')->with('error', __('Plan not found'));
            }
        }


        return redirect()->route('home');
    }

    // Load Dashboard user's using ajax
    public function filterView(Request $request)
    {
        $usr = Auth::user();
        $users = User::where('id', '!=', $usr->id);

        if ($request->ajax()) {
            if (!empty($request->keyword)) {
                $users->where('name', 'LIKE', $request->keyword . '%')->orWhereRaw('FIND_IN_SET("' . $request->keyword . '",skills)');
            }

            $users = $users->get();
            $returnHTML = view('admin.view', compact('users'))->render();

            return response()->json(
                [
                    'success' => true,
                    'html' => $returnHTML,
                ]
            );
        }
    }
}
