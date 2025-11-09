import React from "react";

export default function Dashboard({ usuarios, pratos, onNavigate }) {
  const totalUsuarios = usuarios.length;
  const totalPratos = pratos.length;
  const valorMedioPratos =
    pratos.length > 0
      ? (
          pratos.reduce((acc, p) => acc + parseFloat(p.valor || 0), 0) /
          pratos.length
        ).toFixed(2)
      : "0.00";

  return (
    <div className="min-h-screen bg-gradient-to-br from-purple-100 to-pink-300 p-6">
      <div className="max-w-6xl mx-auto">
        {/* Header */}
        <div className="text-center mb-12">
          <h1 className="text-5xl font-bold text-purple-700 mb-3">
            🍽️ FoodFlow
          </h1>
          <p className="text-xl text-gray-700">
            Sistema de Gerenciamento de Restaurante
          </p>
        </div>

        {/* Cards de Estatísticas */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
          <div className="bg-white rounded-2xl shadow-xl p-6 text-center">
            <div className="text-4xl mb-2">👥</div>
            <h3 className="text-2xl font-bold text-gray-700">
              {totalUsuarios}
            </h3>
            <p className="text-gray-500">Usuários Cadastrados</p>
          </div>

          <div className="bg-white rounded-2xl shadow-xl p-6 text-center">
            <div className="text-4xl mb-2">🍴</div>
            <h3 className="text-2xl font-bold text-gray-700">{totalPratos}</h3>
            <p className="text-gray-500">Pratos no Cardápio</p>
          </div>

          <div className="bg-white rounded-2xl shadow-xl p-6 text-center">
            <div className="text-4xl mb-2">💰</div>
            <h3 className="text-2xl font-bold text-gray-700">
              R$ {valorMedioPratos}
            </h3>
            <p className="text-gray-500">Valor Médio dos Pratos</p>
          </div>
        </div>

        {/* Menu de Navegação */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
          {/* Seção de Usuários */}
          <div className="bg-white rounded-2xl shadow-xl p-8">
            <h2 className="text-2xl font-bold text-blue-700 mb-6 flex items-center">
              <span className="mr-2">👥</span> Gerenciar Usuários
            </h2>
            <div className="space-y-4">
              <button
                onClick={() => onNavigate("cadastro-usuario")}
                className="w-full bg-blue-600 text-white p-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-md"
              >
                ➕ Cadastrar Novo Usuário
              </button>
              <button
                onClick={() => onNavigate("lista-usuarios")}
                className="w-full bg-blue-500 text-white p-3 rounded-lg font-semibold hover:bg-blue-600 transition shadow-md"
              >
                📋 Ver Todos os Usuários
              </button>
            </div>
          </div>

          {/* Seção de Pratos */}
          <div className="bg-white rounded-2xl shadow-xl p-8">
            <h2 className="text-2xl font-bold text-green-700 mb-6 flex items-center">
              <span className="mr-2">🍴</span> Gerenciar Cardápio
            </h2>
            <div className="space-y-4">
              <button
                onClick={() => onNavigate("cadastro-prato")}
                className="w-full bg-green-600 text-white p-3 rounded-lg font-semibold hover:bg-green-700 transition shadow-md"
              >
                ➕ Cadastrar Novo Prato
              </button>
              <button
                onClick={() => onNavigate("lista-pratos")}
                className="w-full bg-green-500 text-white p-3 rounded-lg font-semibold hover:bg-green-600 transition shadow-md"
              >
                📋 Ver Todos os Pratos
              </button>
            </div>
          </div>
        </div>

        {/* Últimos Registros */}
        {(usuarios.length > 0 || pratos.length > 0) && (
          <div className="mt-12 grid grid-cols-1 md:grid-cols-2 gap-6">
            {/* Últimos Usuários */}
            {usuarios.length > 0 && (
              <div className="bg-white rounded-2xl shadow-xl p-6">
                <h3 className="text-xl font-bold text-blue-700 mb-4">
                  Últimos Usuários
                </h3>
                <div className="space-y-3">
                  {usuarios
                    .slice(-3)
                    .reverse()
                    .map((usuario) => (
                      <div
                        key={usuario.id}
                        className="p-3 bg-blue-50 rounded-lg border border-blue-200"
                      >
                        <p className="font-semibold text-gray-800">
                          {usuario.nome}
                        </p>
                        <p className="text-sm text-gray-600">{usuario.cpf}</p>
                      </div>
                    ))}
                </div>
              </div>
            )}

            {/* Últimos Pratos */}
            {pratos.length > 0 && (
              <div className="bg-white rounded-2xl shadow-xl p-6">
                <h3 className="text-xl font-bold text-green-700 mb-4">
                  Últimos Pratos
                </h3>
                <div className="space-y-3">
                  {pratos
                    .slice(-3)
                    .reverse()
                    .map((prato) => (
                      <div
                        key={prato.id}
                        className="p-3 bg-green-50 rounded-lg border border-green-200"
                      >
                        <p className="font-semibold text-gray-800">
                          {prato.nome}
                        </p>
                        <p className="text-sm text-gray-600">
                          R$ {parseFloat(prato.valor).toFixed(2)}
                        </p>
                      </div>
                    ))}
                </div>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}
