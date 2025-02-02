<?php
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public string $reflekt; 
    public $photo;


    public function save(){
    
        if($this->photo){
            $path = $this->photo->store('photos','public');
        } else {
            $path = '';
        }
        
        Auth::user()->reflekts()->create([
            'reflekt' => $this->reflekt,
            'photo' => $path
        ]);

        $this->redirect('/feed');

   }
}; ?>

<div>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-5 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">             

                <form class="max-w-sm mx-auto" wire:submit="save">
                <div class="mb-5">
                    <label for="reflekt" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your Reflektion</label>
                    <input type="text" wire:model="reflekt" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="How are you feeling today?" required />
                </div>
              
                @if($photo)
                <div class="flex items-center justify-center w-full mb-6">
                    <img src="{{ $photo->temporaryUrl() }}" class="object-cover w-64 h-64 rounded-lg" alt="Photo preview">
                </div>
                @else
                <div class="flex items-center justify-center w-full mb-6">
                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-gray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                        </div>
                        <input id="dropzone-file" type="file" class="hidden" wire:model="photo"/>
                    </label>
                </div>
                @endif
                <div
                    x-data="{ uploading: false, progress: 0 }"
                    x-on:livewire-upload-start="uploading = true"
                    x-on:livewire-upload-finish="uploading = false; progress = 0;"
                    x-on:livewire-upload-progress="progress = $event.detail.progress"
                    x-show="uploading"
                >
                    <div class="flex justify-between mb-1">
                        <span class="text-base font-medium text-blue-700 dark:text-white">Photo Uploading...</span>
                        <span class="text-sm font-medium text-blue-700 dark:text-white">{{$progress ?? ''}}%</span>
                    </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                        <div class="bg-blue-600 h-2.5 rounded-full" :style="{ width: `${progress}%` }"></div>
                    </div>
                </div>


                <button type="submit" class="text-white bg-lime-500 hover:bg-lime-600 focus:ring-4 focus:outline-none focus:ring-lime-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                </form>


               

            </div>
        </div>
    </div>
</div>
