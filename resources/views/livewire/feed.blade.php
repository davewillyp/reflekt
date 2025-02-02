<?php
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\Reflekt;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {

    use WithPagination;

    public $perPage = 6;
    
    public function loadMore(){
        $this->perPage += 6;
    }

    public function with(): array{

         return [
               'reflekts' => Reflekt::withCount('likes')->with('likedBy')->with('user')->latest()->paginate($this->perPage)
         ];
        
    }

    public function delete($id){
        Auth::user()->reflekts()->find($id)->delete();
    }

    public function like($id){
         $reflekt = Reflekt::find($id);
         $reflekt->likes()->firstOrCreate([
               'user_id' => Auth::id()
         ]);      
    }

    public function unLike($id){
        $reflekt = Reflekt::find($id);
        $reflekt->likes()->where('user_id', Auth::id())->delete();
    }
  
}; ?>

<div class="max-w-2xl pb-6 mx-auto sm:px-6 lg:px-8">
    <div class="my-5">
        <h1 class="mb-4 text-4xl font-extrabold text-lime-500 md:text-4xl dark:text-white">Your Feed</h1>
    </div>
    <div class="grid gap-4">
   @foreach($reflekts as $reflekt)
   <div class="flex flex-col bg-white rounded-lg shadow-lg" wire:key="{{ $reflekt->id }}">
    <div><img src="{{ $reflekt->photo }}" alt="" class="rounded-t-lg"></div>
    <div class="flex flex-grow px-4 pt-3 pb-2">
       <img class="hidden object-cover h-12 mr-4 rounded-full shadow" src="https://images.unsplash.com/photo-1542156822-6924d1a71ace?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=60" alt="avatar">
       <div class="flex-grow">
          <div class="flex items-center justify-between">
             <h2 class="-mt-1 font-semibold text-gray-500 text-md">{{$reflekt->user->name}} </h2>
             <small class="text-sm text-gray-700">{{ Carbon::parse($reflekt->created_at)->diffForHumans() }}</small>
          </div>
          <p class="text-xs text-gray-400">Joined {{ Carbon::parse($reflekt->user->created_at)->toFormattedDateString() }}</p>
          <p class="mt-3 text-xl font-semibold text-gray-900">
             {{ $reflekt->reflekt }}
          </p>
          <div class="flex items-center mt-4">
            @if(!$reflekt->likedBy->contains('user_id', Auth::id()))
             <div class="flex mr-2 mr-3 text-sm text-gray-500 cursor-pointer hover:text-lime-500"  wire:click="like({{ $reflekt->id }})">
                <svg fill="none" viewBox="0 0 24 24"  class="w-4 h-4 mr-1" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                 </svg>
                <span>@if($reflekt->likes_count) {{ $reflekt->likes_count }} @endif</span>
             </div>
             @else
             <div class="flex mr-2 mr-3 text-sm cursor-pointer text-lime-500 hover:text-gray-500"  wire:click="unLike({{ $reflekt->id }})">
               <svg fill="lime" viewBox="0 0 24 24"  class="w-4 h-4 mr-1" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
               <span>@if($reflekt->likes_count) {{ $reflekt->likes_count }} @endif</span>
            </div>
             @endif
             <div class="flex mr-2 mr-3 text-sm text-gray-500 cursor-pointer hover:text-lime-500">
                <svg fill="none" viewBox="0 0 24 24" class="w-4 h-4 mr-1" stroke="currentColor">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
                <span></span>
             </div> 
             @if($reflekt->user_id == Auth::id())            
             <div class="flex text-sm text-gray-500 cursor-pointer hover:text-red-500" wire:click="delete({{ $reflekt->id }})">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                  </svg>            
             </div>  
             @endif    
          </div>
       </div>
    </div>
 </div>
   @endforeach
   </div>
   <div x-intersect.full="$wire.loadMore()" class="p-4">    
      <div wire:loading.class.remove="hidden" class="hidden">
         <div class="text-center">
            <div role="status">
                <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                </svg>
                <span class="sr-only">Loading...</span>
            </div>
        </div>
      </div>

   </div>
</div>
