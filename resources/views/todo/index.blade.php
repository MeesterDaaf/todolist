<?php /* @var \App\Models\Todo[] $todos */ ?>

@extends('layout.app')

@section('content')


    <div class="container">
        <ul class="todo-list">
            @foreach($todos as $todo)
                <li class="todo-item">
                    <form class="todo-item__form" action="{{route('todos.update', $todo)}}" method="post">
                        @method('PUT')
                        @csrf
                        <input type="checkbox" name="done" {{ $todo->done ? 'checked' : null }}>
                        <button type="submit" name="">send</button>
                    </form>
                    <div class="todo-item__content">
                        <h3>{{ ucfirst($todo->title) }}</h3>
                        <p>{{ $todo->content }}</p>
                        
                    </div>
                    <div class="todo__children">
                        @isset($todo->children)
                            <ul class="todo-list__children">
                                @forelse ($todo->children as $item)
                                    <li>
                                        <div class="child__title">
                                            {{ $item->title }}
                                        </div>
                                        <div class="child__form">
                                            <form class="todo-item__form" action="{{route('todos.update', $item)}}" method="post">
                                                @method('PUT')
                                                @csrf
                                                <input type="checkbox" name="done" {{ $item->done ? 'checked' : null }}>
                                                <button type="submit" name="">send</button>
                                            </form>
                                        </div>
                                    </li>
                                @empty
                                    <p>No todo items</p>
                            @endforelse
                            </ul>
                        @endisset
                    </div>
                </li>
            @endforeach
        </ul>

        <h2>Create To-do</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{route('todos.store')}}" method="post" class="create-todo">
            @csrf
            <div class="create-todo__input-group">
                <label for="title">Title</label>
                <input id="title" type="text" name="title" value="{{ old('title') }}">
            </div>
            <div class="create-todo__input-group">
                <label for="content">Description</label>
                <textarea id="content" name="content"cols="30" rows="10">{{ old('content') }}</textarea>
            </div>
            <div class="create-todo__input-group">
                <label for="parent">Choose parent</label>
                <select name="parent" id="">
                    <option value="0" selected>NO PARENT</option>
                    @foreach ($todos as $item)
                        <option value="{{$item->id}}">{{$item->title}}</option>
                    @endforeach
                </select>
            </div>
            <button class="button" type="submit">Save</button>
        </form>
    </div>
@endsection
