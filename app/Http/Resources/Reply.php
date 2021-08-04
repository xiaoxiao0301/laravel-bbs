<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Reply extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $include = $request->get("include");
        if (strstr($include, "topic")) {
            [$userTrue, $topicTrue] = explode(",", $request->get("include"));
        } else {
            $userTrue = $include;
            $topicTrue = "";
        }

        return [
            'id' => $this->id,
            'user_id' => (int) $this->user_id,
            'topic_id' => (int) $this->topic_id,
            'content' => $this->content,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'user' => $this->when($userTrue == "user", function () {
                return $this->user;
            }),
            'topic' => $this->when($topicTrue == "topic", function () {
                return $this->topic;
            }),
        ];
    }
}
