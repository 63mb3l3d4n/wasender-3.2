<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Template;
use App\Models\User;
use DB;
use Auth;
use App\Traits\Whatsapp;
use Cache;
class ChatController extends Controller
{
    use Whatsapp;

    public function chats($id)
    {
        $device = Device::where("user_id", Auth::id())
            ->where("status", 1)
            ->where("uuid", $id)
            ->first();
        abort_if(empty($device), 404);
        $templates = Template::where("user_id", Auth::id())
            ->where("status", 1)
            ->latest()
            ->get();
        return view("user.chats.list", compact("device", "templates"));
    }

    public function getGroupMetaData(Request $request)
    {
        $device = Device::where("user_id", Auth::id())
            ->where("status", 1)
            ->where("uuid", $request->device_id)
            ->first();

        abort_if(empty($device), 404);

        $metaData = Cache::remember(
            "groups_" . $device->uuid . $request->id,
            520,
            function () use ($device, $request) {
                return $this->groupMetaData($request->id, $device->id);
            }
        );

        return response()->json($metaData);
    }

    public function sendGroupBulkMessage(Request $request, $id)
    {
        if (getUserPlanData("messages_limit") == false) {
            return response()->json(
                [
                    "message" => __("Maximum Monthly Messages Limit Exceeded"),
                ],
                401
            );
        }
        $device = Device::where("user_id", Auth::id())
            ->where("status", 1)
            ->where("uuid", $id)
            ->first();
        abort_if(empty($device), 404);

        if (count($request->groups) == 0) {
            return response()->json(
                [
                    "message" => __("Select Some Groups"),
                ],
                401
            );
        }

        $validated = $request->validate([
            "selecttype" => "required",
        ]);

        $success_requests = 0;
        $faild_requests = 0;
        $user = User::where("id", Auth::id())->first();

        if ($request->selecttype == "template") {
            $validated = $request->validate([
                "template" => "required",
            ]);
            $template = Template::where("user_id", Auth::id())
                ->where("status", 1)
                ->findorFail($request->template);

            foreach ($request->groups as $key => $group) {
                $isGroup = explode("@", $group);
                $isGroup = $isGroup[1];
                abort_if($isGroup != "g.us", 404);

                if (isset($template->body["text"])) {
                    $body = $template->body;
                    

                    $text = $this->formatText(
                        $template->body["text"],
                        [],
                        $user
                    );
                    $body["text"] = $text;
                } else {
                    $body = $template->body;
                }
                $type = $template->type;

                try {
                    $response = $this->sendMessageToGroup(
                        $body,
                        $device->id,
                        $group,
                        $type,
                        true,
                        env('DELAY_TIME',0)
                    );

                    if ($response["status"] == 200) {
                        $logs["user_id"] = Auth::id();
                        $logs["device_id"] = $device->id;
                        $logs["from"] = $device->phone ?? null;
                        $logs["to"] = "Group : " . $request->group_name;
                        $logs["template_id"] = $template->id ?? null;
                        $logs["type"] = "single-send";
                        $this->saveLog($logs);

                        
                        $success_requests = $success_requests+1;
                    } else {
                        
                        $faild_requests = $faild_requests+1;
                        
                    }
                } catch (Exception $e) {
                    $faild_requests = $faild_requests+1;
                }
            }
        } else {
            $validated = $request->validate([
                "message" => "required|max: 2000",
            ]);

            $text = $this->formatText($request->message);
            $body["text"] = $text;
            $type = "plain-text";

             foreach ($request->groups as $key => $group) {

                $isGroup = explode("@", $group);
                $isGroup = $isGroup[1];
                abort_if($isGroup != "g.us", 404);

                try {
                    $response = $this->sendMessageToGroup(
                        $body,
                        $device->id,
                        $group,
                        $type,
                        true,
                        env('DELAY_TIME',0)
                    );

                    if ($response["status"] == 200) {
                        $logs["user_id"] = Auth::id();
                        $logs["device_id"] = $device->id;
                        $logs["from"] = $device->phone ?? null;
                        $logs["to"] = "Group : " . $request->group_name;
                        $logs["template_id"] = $template->id ?? null;
                        $logs["type"] = "single-send";
                        $this->saveLog($logs);

                       $success_requests = $success_requests+1;
                    } else {
                        $faild_requests = $faild_requests+1;
                    }
                } catch (Exception $e) {
                   $faild_requests = $faild_requests+1;
                }

           }
        }

        return response()->json(
            [
                "message" => __("Total Message Sent in (".$success_requests.") Groups. Total Sending Faild in (".$faild_requests.") Groups"),
            ],
            200
        );
    }

    public function sendMessage(Request $request, $id)
    {
        if (getUserPlanData("messages_limit") == false) {
            return response()->json(
                [
                    "message" => __("Maximum Monthly Messages Limit Exceeded"),
                ],
                401
            );
        }

        $device = Device::where("user_id", Auth::id())
            ->where("status", 1)
            ->where("uuid", $id)
            ->first();
        abort_if(empty($device), 404);

        $validated = $request->validate([
            "reciver" => "required|max:20",
            "selecttype" => "required",
        ]);

        if ($request->selecttype == "template") {
            $validated = $request->validate([
                "template" => "required",
            ]);
            $template = Template::where("user_id", Auth::id())
                ->where("status", 1)
                ->findorFail($request->template);

            if (isset($template->body["text"])) {
                $body = $template->body;
                $user = User::where("id", Auth::id())->first();

                $text = $this->formatText($template->body["text"], [], $user);
                $body["text"] = $text;
            } else {
                $body = $template->body;
            }
            $type = $template->type;
        } else {
            $validated = $request->validate([
                "message" => "required|max: 500",
            ]);

            $text = $this->formatText($request->message);
            $body["text"] = $text;
            $type = "plain-text";
        }

        if (!isset($body)) {
            return response()->json(["error" => "Request Failed"], 401);
        }

        try {
            $response = $this->messageSend(
                $body,
                $device->id,
                $request->reciver,
                $type,
                true
            );

            if ($response["status"] == 200) {
                $logs["user_id"] = Auth::id();
                $logs["device_id"] = $device->id;
                $logs["from"] = $device->phone ?? null;
                $logs["to"] = $request->reciver;
                $logs["template_id"] = $template->id ?? null;
                $logs["type"] = "single-send";
                $this->saveLog($logs);

                return response()->json(
                    [
                        "message" => __("Message sent successfully..!!"),
                    ],
                    200
                );
            } else {
                return response()->json(["error" => "Request Failed"], 401);
            }
        } catch (Exception $e) {
            return response()->json(["error" => "Request Failed"], 401);
        }
    }

    public function chatHistory($id)
    {
        $device = Device::where("user_id", Auth::id())
            ->where("status", 1)
            ->where("uuid", $id)
            ->first();
        abort_if(empty($device), 404);

        $response = Cache::remember(
            "groups_" . $device->uuid,
            120,
            function () use ($device) {
                return $this->getChats($device->id);
            }
        );
        if ($response["status"] == 200) {
            $data["chats"] = $response["data"];
            $data["device_name"] = $device->name;
            $data["phone"] = $device->phone;
            return response()->json($data);
        }

        $data["message"] = $response["message"];
        $data["status"] = $response["status"];

        return response()->json($data, 401);
    }

    public function groups($id)
    {
        $device = Device::where("user_id", Auth::id())
            ->where("status", 1)
            ->where("uuid", $id)
            ->first();
        abort_if(empty($device), 404);
        $templates = Template::where("user_id", Auth::id())
            ->where("status", 1)
            ->latest()
            ->get();
        return view("user.chats.groups", compact("device", "templates"));
    }

    public function groupHistory($id)
    {
        $device = Device::where("user_id", Auth::id())
            ->where("status", 1)
            ->where("uuid", $id)
            ->first();
        abort_if(empty($device), 404);

        $response = $this->getGroupList($device->id);

        if ($response["status"] == 200) {
            $data["chats"] = $response["data"];
            $data["device_name"] = $device->name;
            $data["phone"] = $device->phone;
            return response()->json($data);
        }

        $data["message"] = $response["message"];
        $data["status"] = $response["status"];

        return response()->json($data, 401);
    }

    public function sendGroupMessage(Request $request, $id)
    {
        $device = Device::where("user_id", Auth::id())
            ->where("status", 1)
            ->where("uuid", $id)
            ->first();
        abort_if(empty($device), 404);

        $validated = $request->validate([
            "group" => "required|max:50",
            "group_name" => "required|max:100",
            "selecttype" => "required",
        ]);

        $isGroup = explode("@", $request->group);
        $isGroup = $isGroup[1];
        abort_if($isGroup != "g.us", 404);

        if ($request->selecttype == "template") {
            $validated = $request->validate([
                "template" => "required",
            ]);

            $template = Template::where("user_id", Auth::id())
                ->where("status", 1)
                ->findorFail($request->template);

            if (isset($template->body["text"])) {
                $body = $template->body;
                $user = User::where("id", Auth::id())->first();

                $text = $this->formatText($template->body["text"], [], $user);
                $body["text"] = $text;
            } else {
                $body = $template->body;
            }
            $type = $template->type;
        } else {
            $validated = $request->validate([
                "message" => "required|max: 500",
            ]);

            $text = $this->formatText($request->message);
            $body["text"] = $text;
            $type = "plain-text";
        }

        if (!isset($body)) {
            return response()->json(["error" => "Request Failed"], 401);
        }

        try {
            $response = $this->sendMessageToGroup(
                $body,
                $device->id,
                $request->group,
                $type,
                true,
                0
            );

            if ($response["status"] == 200) {
                $logs["user_id"] = Auth::id();
                $logs["device_id"] = $device->id;
                $logs["from"] = $device->phone ?? null;
                $logs["to"] = "Group : " . $request->group_name;
                $logs["template_id"] = $template->id ?? null;
                $logs["type"] = "single-send";
                $this->saveLog($logs);

                return response()->json(
                    [
                        "message" => __("Message sent successfully..!!"),
                    ],
                    200
                );
            } else {
                return response()->json(["error" => "Request Failed"], 401);
            }
        } catch (Exception $e) {
            return response()->json(["error" => "Request Failed"], 401);
        }
    }
}
