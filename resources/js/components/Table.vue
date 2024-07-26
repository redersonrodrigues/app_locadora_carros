<template>
    <div>
        <!-- {{ this.$store.state.teste }} -->
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="(t, key) in titulos" :key="key">{{ t.titulo }}</th>
                    <th v-if="visualizar.visivel || atualizar || remover.visivel"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(obj, chave) in dadosFiltrados" :key="chave">
                    <td v-for="(valor, chaveValor) in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto'">{{ valor }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{ '...' + valor }}</span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img :src="'/storage/' + valor" width="30" height="30">
                        </span>
                    </td>
                    <td v-if="visualizar.visivel || atualizar || remover.visivel">
                        <div class="d-flex justify-content-between">
                            <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm mx-1"
                                :data-bs-toggle="visualizar.dataBsToggle" :data-bs-target="visualizar.dataBsTarget"
                                @click="setStore(obj)">Visualizar</button>
                            <button v-if="atualizar" class="btn btn-outline-primary btn-sm mx-1">Atualizar</button>
                            <button v-if="remover.visivel" class="btn btn-outline-danger btn-sm mx-1" 
                            :data-bs-toggle="remover.dataBsToggle" :data-bs-target="remover.dataBsTarget" @click="setStore(obj)">Remover</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ['dados', 'titulos', 'visualizar', 'atualizar', 'remover'],
    methods: {
        setStore(obj) {
            // console.log(obj);
            this.$store.state.item = obj 
        }
    },
    computed: {
        dadosFiltrados() {
            let campos = Object.keys(this.titulos);
            let dadosFiltrados = [];

            this.dados.forEach((item) => {
                let itemFiltrado = {};
                campos.forEach(campo => {
                    itemFiltrado[campo] = item[campo] || ''; // Atribui um valor padrão se o campo não existir
                });
                dadosFiltrados.push(itemFiltrado);
            });

            return dadosFiltrados; // Retorna um array de objetos
        }
    }
}
</script>
