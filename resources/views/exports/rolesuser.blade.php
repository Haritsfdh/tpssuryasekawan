<table>
  <thead>
    <tr>
      <th>Name</th>
      <th>Roles</th>
    </tr>
  </thead>
  <tbody>
   @forelse ($items as $item)
     @php
        $roleCount = $item->roles->count();
     @endphp
     <tr>
      <td @if($roleCount > 0) rowspan="{{ $roleCount }}" @endif      
          style="vertical-align: top;">{{ Str::title($item->name) }}</td>      
      @forelse ($item->roles->sortBy('name') as $role)
        <td>{{ $role->name }}</td>
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