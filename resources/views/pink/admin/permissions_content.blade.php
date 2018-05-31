<div id="content-page" class="content group">
    <div class="hentry group">
        <h3 class="title_page">Привелегии</h3>
        <form action="{{ route('admin.permissions.store') }}" method="post">
            {{ csrf_field() }}
            <div class="short-table white">
                <table style="width: 100%">
                    <thead>
                        <th>Привелегии</th>
                    @if(!$roles->isEmpty())
                        @foreach($roles as $item)
                            <th>{{ $item->name }}</th>
                        @endforeach
                    @endif

                    </thead>
                    <tbody>
                    @if(!$priv->isEmpty())
                        @foreach($priv as $value)
                            <tr>
                                <td>{{ $value->name }}</td>
                                @foreach($roles as $role)
                                    <td>
                                        @if($role->hasPermission($value->name))
                                            <input checked type="checkbox" value="">
                                        @else
                                            <input  type="checkbox" value="">
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>