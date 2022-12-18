<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            投稿の個別表示
        </h2>

        <x-message :message="session('message')" />

    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mx-4 sm:p-8">
            <div class="px-10 mt-4">

                <div class="bg-white w-full  rounded-2xl px-10 py-8 shadow-lg hover:shadow-2xl transition duration-500">
                    <div class="mt-4">
                        <h1 class="text-lg text-gray-700 font-semibold hover:underline cursor-pointer">
                            {{ $post->title }}
                        </h1>
                        <hr class="w-full">
                </div>
                <div class="flex justify-end mt-4">
                    <a href="{{route('post.edit', $post)}}"><x-button class="w-24 bg-teal-700 float-right text-white p-2 rounded">編集</x-button></a>
                    <form method="post" action="{{route('post.destroy', $post)}}">
                        @csrf
                        @method('delete')
                        <x-button class="w-24 bg-red-700 float-right text-white p-2 rounded ml-4" onClick="return confirm('本当に削除しますか？');">削除</x-button>
                    </form>
                </div>
                {{-- div 追加部分 --}}
                <div>
                    <p class="mt-4 text-gray-600 py-4">{{$post->body}}</p>
                    @if($post->image)
                        <div>
                            (画像ファイル：{{$post->image}})
                        </div>
                        <img src="{{ asset('storage/images/'.$post->image)}}" class="mx-auto" style="height:300px;">
                    @endif
                    <span>
                        <img src="{{ asset('img/nicebutton.png') }}" width="30px">
                        
                        <!-- もし$likeがあれば＝ユーザーが「いいね」をしていたら -->
                        @if ($post->likes->count() >= 1)
                            <!-- 「いいね」取消用ボタンを表示 -->
                            <a href="{{ route('unlike', $post) }}" class="btn btn-success btn-sm">
                                いいね
                                <!-- 「いいね」の数を表示 -->
                                <span class="badge">
                                    {{ $post->likes->count() }}
                                </span>
                            </a>
                            <!-- まだユーザーが「いいね」をしていなければ、「いいね」ボタンを表示 -->
                        @elseif($post->likes->count() <= 0)
                            <a href="{{ route('like', $post) }}" class="btn btn-secondary btn-sm">
                                いいね
                                <!-- 「いいね」の数を表示 -->
                                <span class="badge">
                                    {{ $post->likes->count() }}
                                </span>
                            </a>
                        @endif
                    </span>
                    <div class="text-sm font-semibold flex flex-row-reverse">
                        <p> {{ $post->user->name}} • {{$post->created_at->diffForHumans()}}</p>
                    </div>
                </div>
            {{-- /div 追加部分 --}}
            </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
