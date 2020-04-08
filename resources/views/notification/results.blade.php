<div class="table-responsive">
    <table class="table table-condensed table-striped table-bordered">
        <thead>
            <tr>
                <th scope="col">@lang('notification.title')</th>
                <th scope="col">@lang('notification.message')</th>
                <th scope="col">@lang('notification.date')</th>
                <th scope="col">@lang('notification.read')</th>
                <th scope="col">@lang('notification.delete')</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $notification)
                <tr>
                    <td data-label="@lang('notification.title')">
                        <a href="{{ route('notifications.show', ['id' => $notification->id]) }}" class="clearfix">
                            <span class="notification-title">{{ $notification->data['title'] }}</span>
                        </a>
                    </td>
                    <td data-label="@lang('notification.message')">
                        <span class="notification-message">{{ $notification->data['body'] }}</span>
                    </td>
                    <td data-label="@lang('notification.date')">
                        <span class="notification-ago">{{ $notification->created_at->diffForHumans() }}</span>
                    </td>
                    <td data-label="@lang('notification.read')">
                        <form action="{{ route('notifications.update', ['id' => $notification->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-xxs btn-success" data-toggle="tooltip"
                                data-original-title="@lang('notification.mark-read')" @if ($notification->read_at != null)
                                disabled @endif>
                                <i class="{{ config('other.font-awesome') }} fa-eye"></i>
                            </button>
                        </form>
                    </td>
                    <td data-label="@lang('notification.delete')">
                        <form action="{{ route('notifications.destroy', ['id' => $notification->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-xxs btn-danger" data-toggle="tooltip"
                                data-original-title="@lang('notification.delete')">
                                <i class="{{ config('other.font-awesome') }} fa-times"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                @lang('notification.no-notifications').
            @endforelse
        </tbody>
    </table>
    <div class="text-center">{{ $notifications->links() }}</div>
</div>
