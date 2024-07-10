<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="(t, key) in titulos" :key="key">{{ t.titulo }}</th>
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
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        dados: {
            type: Array,
            default: () => []
        },
        titulos: {
            type: Object,
            default: () => ({})
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
