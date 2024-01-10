<div class="agent-container">
    @foreach ($agents as $agent)
        <div class="card-container agent">
            <i class="fa-solid fa-circle-xmark agent-mobile fa-2x"></i>
            <div class="user-image agent">
                <img src="{{ $agent->image }}" alt="Avatar of {{ $agent->fname }} {{ $agent->lname }}">
            </div>
            <div class="card-form agent">
                <div class="input-group">
                    <p>Firstname :</p>
                    <p>{{ $agent->user->fname }}</p>
                </div>
                <div class="input-group">
                    <p>Lastname :</p>
                    <p>{{ $agent->user->lname }}</p>
                </div>
                <div class="input-group">
                    <p>Email :</p>
                    <p><a href="mailto:{{ $agent->user->email }}">{{ $agent->user->email }}</a></p>
                </div>
                <div class="input-group">
                    <p>Currently assigned properties :</p>
                </div>
                @foreach ($agent->properties as $agentProp)
                    <div class="input-group">
                        <p><a href="properties/edit/{{ $agentProp->property_id }}"
                                title="Edit property {{ $agentProp->title }}">{{ $agentProp->title }}</a></p>
                        <p class="xmark-agent"><a
                                href="users/unassignagent/{{ $agent->agent_id }}-{{ $agentProp->property_id }}"
                                title="Unassign agent : {{ $agent->user->fname }} {{ $agent->user->lname }} from {{ $agentProp->title }}"><i
                                    class="fa-solid fa-xmark"></i></a></p>
                    </div>
                @endforeach
                <a href="users/delete/{{ $agent->user_id }}"><i class="fa-solid fa-circle-xmark agent fa-2x"></i></a>

                <form action="users/assignagent/{{ $agent->agent_id }}" method="post">
                    <label for="agent_property_{{ $agent->agent_id }}">Properties to assign</label>
                    <div class="input-group">
                        <select style="width:100%;" name="agent_property[]" id="agent_property_{{ $agent->agent_id }}"
                            multiple="multiple">
                            @if (isset($agent->assignableProperties) && !empty($agent->assignableProperties))
                                @foreach ($agent->assignableProperties as $assignableProperty)
                                    <option value="{{ $assignableProperty->property_id }}">
                                        {{ $assignableProperty->title }}
                                    </option>
                                @endforeach
                            @else
                                <option value="">No property to assign</option>
                            @endif
                        </select>
                    </div>
                    <input type="hidden" name="agent_id" value={{ $agent->agent_id }}>
                    <input type="hidden" name="manager_id" value={{ $agent->manager_id }}>
                    <input type="submit" value="Assign agent to properties">
                </form>
            </div>
        </div>
    @endforeach
</div>
