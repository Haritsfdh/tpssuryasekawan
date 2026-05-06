<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Permissions</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($items as $item)
      @php
        $pCount = $item->permissions->count();
      @endphp
      <tr>
        <td @if($pCount > 0) rowspan="{{ $pCount }}" @endif
            style="vertical-align: top;">{{ $item->name }}</td>
        @forelse ($item->permissions as $permission)
          <td>{{ $permission->name }}</td>
          @if(!$loop->last)
          </tr>
          <tr>
          @endif
        @empty
        @endforelse        
      </tr>
    @empty
      
    @endforelse
  </tbody>
</table>