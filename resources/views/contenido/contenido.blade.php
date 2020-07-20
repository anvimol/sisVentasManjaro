@extends('principal')
@section('contenido')
    <template v-if="menu==0">
        <h1>Contenido del Menu 0</h1>
    </template>

    <template v-if="menu==1">
        <categoria></categoria>
    </template>

    <template v-if="menu==2">
        <articulo></articulo>
    </template>

    <template v-if="menu==3">
        <h1>Contenido del Menu 3</h1>
    </template>

    <template v-if="menu==4">
        <proveedor></proveedor>
    </template>

    <template v-if="menu==5">
        <h1>Contenido del Menu 5</h1>
    </template>

    <template v-if="menu==6">
        <cliente></cliente>
    </template>

    <template v-if="menu==7">
        <user></user>
    </template>

    <template v-if="menu==8">
        <rol></rol>
    </template>

    <template v-if="menu==9">
        <h1>Contenido del Menu 9</h1>
    </template>

    <template v-if="menu==10">
        <h1>Contenido del Menu 10</h1>
    </template>

    <template v-if="menu==11">
        <h1>Contenido del Menu 11</h1>
    </template>

    <template v-if="menu==12">
        <h1>Contenido del Menu 12</h1>
    </template>
@endsection