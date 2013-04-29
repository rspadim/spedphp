<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions name="NfeRetRecepcao2" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:ns1="http://schemas.xmlsoap.org/soap/http" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <wsdl:types>
<xs:schema elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" version="1.0" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:import namespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2"/>
<xs:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
<xs:element name="nfeDadosMsg">
<xs:complexType mixed="true">
<xs:sequence>
<xs:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="nfeRetRecepcao2Result" type="tns:nfeRetRecepcao2Result"/>
<xs:complexType mixed="true" name="nfeRetRecepcao2Result">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" ref="retConsReciNFe"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="nfeCabecMsg">
<xs:sequence>
<xs:element minOccurs="0" name="cUF" type="xs:string"/>
<xs:element minOccurs="0" name="versaoDados" type="xs:string"/>
</xs:sequence>
<xs:anyAttribute namespace="##other" processContents="skip"/>
</xs:complexType>
</xs:schema>
<xs:schema elementFormDefault="unqualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2" version="1.0" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:element name="Signature" type="SignatureType"/>
<xs:element name="retConsReciNFe" type="TRetConsReciNFe"/>
<xs:complexType name="TRetConsReciNFe">
<xs:sequence>
<xs:element name="tpAmb" type="xs:string"/>
<xs:element name="verAplic" type="xs:string"/>
<xs:element name="nRec" type="xs:string"/>
<xs:element name="cStat" type="xs:string"/>
<xs:element name="xMotivo" type="xs:string"/>
<xs:element name="cUF" type="xs:string"/>
<xs:element maxOccurs="unbounded" minOccurs="0" name="protNFe" nillable="true" type="TProtNFe"/>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
<xs:attribute name="xmlns" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="TProtNFe">
<xs:sequence>
<xs:element name="infProt">
<xs:complexType>
<xs:sequence>
<xs:element name="tpAmb" type="xs:string"/>
<xs:element name="verAplic" type="xs:string"/>
<xs:element name="chNFe" type="xs:string"/>
<xs:element name="dhRecbto" type="xs:anySimpleType"/>
<xs:element minOccurs="0" name="nProt" type="xs:string"/>
<xs:element minOccurs="0" name="digVal" type="xs:string"/>
<xs:element name="cStat" type="xs:string"/>
<xs:element name="xMotivo" type="xs:string"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="Signature" type="SignatureType"/>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="SignatureType">
<xs:sequence>
<xs:element name="SignedInfo" type="SignedInfoType"/>
<xs:element name="SignatureValue" type="SignatureValueType"/>
<xs:element name="KeyInfo" type="KeyInfoType"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
</xs:complexType>
<xs:complexType name="SignedInfoType">
<xs:sequence>
<xs:element name="CanonicalizationMethod">
<xs:complexType>
<xs:sequence/>
<xs:attribute name="Algorithm" type="xs:anyURI" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="SignatureMethod">
<xs:complexType>
<xs:sequence/>
<xs:attribute name="Algorithm" type="xs:anyURI" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="Reference" type="ReferenceType"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
</xs:complexType>
<xs:complexType name="ReferenceType">
<xs:sequence>
<xs:element name="Transforms" type="TransformsType"/>
<xs:element name="DigestMethod">
<xs:complexType>
<xs:sequence/>
<xs:attribute name="Algorithm" type="xs:anyURI" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="DigestValue" type="xs:base64Binary"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
<xs:attribute name="URI" type="xs:string" use="required"/>
<xs:attribute name="Type" type="xs:anyURI"/>
</xs:complexType>
<xs:complexType name="TransformsType">
<xs:sequence>
<xs:element maxOccurs="unbounded" name="Transform" type="TransformType"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TransformType">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" name="XPath" type="xs:string"/>
</xs:sequence>
<xs:attribute name="Algorithm" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="SignatureValueType">
<xs:simpleContent>
<xs:extension base="xs:base64Binary">
<xs:attribute name="Id" type="xs:ID"/>
</xs:extension>
</xs:simpleContent>
</xs:complexType>
<xs:complexType name="KeyInfoType">
<xs:sequence>
<xs:element name="X509Data" type="X509DataType"/>
</xs:sequence>
<xs:attribute name="Id" type="xs:ID"/>
</xs:complexType>
<xs:complexType name="X509DataType">
<xs:sequence>
<xs:element name="X509Certificate" type="xs:base64Binary"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TEnderEmi">
<xs:sequence>
<xs:element name="xLgr" type="xs:string"/>
<xs:element name="nro" type="xs:string"/>
<xs:element minOccurs="0" name="xCpl" type="xs:string"/>
<xs:element name="xBairro" type="xs:string"/>
<xs:element name="cMun" type="xs:string"/>
<xs:element name="xMun" type="xs:string"/>
<xs:element name="UF" type="tUfEmi"/>
<xs:element minOccurs="0" name="CEP" type="xs:string"/>
<xs:element minOccurs="0" name="cPais" type="xs:string"/>
<xs:element minOccurs="0" name="xPais" type="xs:string"/>
<xs:element minOccurs="0" name="fone" type="xs:string"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TRetEnviNFe">
<xs:sequence>
<xs:element name="tpAmb" type="xs:string"/>
<xs:element name="verAplic" type="xs:string"/>
<xs:element name="cStat" type="xs:string"/>
<xs:element name="xMotivo" type="xs:string"/>
<xs:element name="cUF" type="xs:string"/>
<xs:element minOccurs="0" name="infRec">
<xs:complexType>
<xs:sequence>
<xs:element name="nRec" type="xs:string"/>
<xs:element name="dhRecbto" type="xs:anySimpleType"/>
<xs:element name="tMed" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="TEndereco">
<xs:sequence>
<xs:element name="xLgr" type="xs:string"/>
<xs:element name="nro" type="xs:string"/>
<xs:element minOccurs="0" name="xCpl" type="xs:string"/>
<xs:element name="xBairro" type="xs:string"/>
<xs:element name="cMun" type="xs:string"/>
<xs:element name="xMun" type="xs:string"/>
<xs:element name="UF" type="tUf"/>
<xs:element minOccurs="0" name="CEP" type="xs:string"/>
<xs:element minOccurs="0" name="cPais" type="xs:string"/>
<xs:element minOccurs="0" name="xPais" type="xs:string"/>
<xs:element minOccurs="0" name="fone" type="xs:string"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TVeiculo">
<xs:sequence>
<xs:element name="placa" type="xs:string"/>
<xs:element name="UF" type="tUf"/>
<xs:element minOccurs="0" name="RNTC" type="xs:string"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TLocal">
<xs:sequence>
<xs:element name="CNPJ" type="xs:string"/>
<xs:element name="CPF" type="xs:string"/>
<xs:element name="xLgr" type="xs:string"/>
<xs:element name="nro" type="xs:string"/>
<xs:element minOccurs="0" name="xCpl" type="xs:string"/>
<xs:element name="xBairro" type="xs:string"/>
<xs:element name="cMun" type="xs:string"/>
<xs:element name="xMun" type="xs:string"/>
<xs:element name="UF" type="tUf"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TConsReciNFe">
<xs:sequence>
<xs:element name="tpAmb" type="xs:string"/>
<xs:element name="nRec" type="xs:string"/>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="TNFe">
<xs:sequence>
<xs:element name="infNFe">
<xs:complexType>
<xs:sequence>
<xs:element name="ide">
<xs:complexType>
<xs:sequence>
<xs:element name="cUF" type="xs:string"/>
<xs:element name="cNF" type="xs:string"/>
<xs:element name="natOp" type="xs:string"/>
<xs:element name="indPag" type="xs:string"/>
<xs:element name="mod" type="xs:string"/>
<xs:element name="serie" type="xs:string"/>
<xs:element name="nNF" type="xs:string"/>
<xs:element name="dEmi" type="xs:string"/>
<xs:element minOccurs="0" name="dSaiEnt" type="xs:string"/>
<xs:element minOccurs="0" name="hSaiEnt" type="xs:string"/>
<xs:element name="tpNF" type="xs:string"/>
<xs:element name="cMunFG" type="xs:string"/>
<xs:element maxOccurs="unbounded" minOccurs="0" name="NFref">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="refNFe" type="xs:string"/>
<xs:element minOccurs="0" name="refNF">
<xs:complexType>
<xs:sequence>
<xs:element name="cUF" type="xs:string"/>
<xs:element name="AAMM" type="xs:string"/>
<xs:element name="CNPJ" type="xs:string"/>
<xs:element name="mod" type="xs:string"/>
<xs:element name="serie" type="xs:string"/>
<xs:element name="nNF" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="refNFP">
<xs:complexType>
<xs:sequence>
<xs:element name="cUF" type="xs:string"/>
<xs:element name="AAMM" type="xs:string"/>
<xs:element minOccurs="0" name="CNPJ" type="xs:string"/>
<xs:element minOccurs="0" name="CPF" type="xs:string"/>
<xs:element name="IE" type="xs:string"/>
<xs:element name="mod" type="xs:string"/>
<xs:element name="serie" type="xs:string"/>
<xs:element name="nNF" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="refCTe" type="xs:string"/>
<xs:element minOccurs="0" name="refECF">
<xs:complexType>
<xs:sequence>
<xs:element name="mod" type="xs:string"/>
<xs:element name="nECF" type="xs:string"/>
<xs:element name="nCOO" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="tpImp" type="xs:string"/>
<xs:element name="tpEmis" type="xs:string"/>
<xs:element name="cDV" type="xs:string"/>
<xs:element name="tpAmb" type="xs:string"/>
<xs:element name="finNFe" type="xs:string"/>
<xs:element name="procEmi" type="xs:string"/>
<xs:element name="verProc" type="xs:string"/>
<xs:element minOccurs="0" name="dhCont" type="xs:anySimpleType"/>
<xs:element minOccurs="0" name="xJust" type="xs:anyType"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="emit">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="CNPJ" type="xs:string"/>
<xs:element minOccurs="0" name="CPF" type="xs:string"/>
<xs:element name="xNome" type="xs:string"/>
<xs:element minOccurs="0" name="xFant" type="xs:string"/>
<xs:element name="enderEmit" type="TEnderEmi"/>
<xs:element name="IE" type="xs:string"/>
<xs:element minOccurs="0" name="IEST" type="xs:string"/>
<xs:element minOccurs="0" name="IM" type="xs:string"/>
<xs:element minOccurs="0" name="CNAE" type="xs:string"/>
<xs:element name="CRT" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="avulsa">
<xs:complexType>
<xs:sequence>
<xs:element name="CNPJ" type="xs:string"/>
<xs:element name="xOrgao" type="xs:string"/>
<xs:element name="matr" type="xs:string"/>
<xs:element name="xAgente" type="xs:string"/>
<xs:element name="fone" type="xs:string"/>
<xs:element name="UF" type="tUfEmi"/>
<xs:element name="nDAR" type="xs:string"/>
<xs:element name="dEmi" type="xs:string"/>
<xs:element name="vDAR" type="xs:string"/>
<xs:element name="repEmi" type="xs:string"/>
<xs:element minOccurs="0" name="dPag" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="dest">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="CNPJ" type="xs:string"/>
<xs:element minOccurs="0" name="CPF" type="xs:string"/>
<xs:element name="xNome" type="xs:string"/>
<xs:element name="enderDest" type="TEndereco"/>
<xs:element name="IE" type="xs:string"/>
<xs:element minOccurs="0" name="ISUF" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="retirada" type="TLocal"/>
<xs:element minOccurs="0" name="entrega" type="TLocal"/>
<xs:element maxOccurs="unbounded" name="det">
<xs:complexType>
<xs:sequence>
<xs:element name="prod">
<xs:complexType>
<xs:sequence>
<xs:element name="cProd" type="xs:string"/>
<xs:element name="cEAN" type="xs:string"/>
<xs:element name="xProd" type="xs:string"/>
<xs:element name="NCM" type="xs:string"/>
<xs:element minOccurs="0" name="EXTIPI" type="xs:string"/>
<xs:element name="CFOP" type="xs:string"/>
<xs:element name="uCom" type="xs:string"/>
<xs:element name="qCom" type="xs:string"/>
<xs:element name="vUnCom" type="xs:string"/>
<xs:element name="vProd" type="xs:string"/>
<xs:element name="cEANTrib" type="xs:string"/>
<xs:element name="uTrib" type="xs:string"/>
<xs:element name="qTrib" type="xs:string"/>
<xs:element name="vUnTrib" type="xs:string"/>
<xs:element minOccurs="0" name="vFrete" type="xs:string"/>
<xs:element minOccurs="0" name="vSeg" type="xs:string"/>
<xs:element minOccurs="0" name="vDesc" type="xs:string"/>
<xs:element minOccurs="0" name="vOutro" type="xs:string"/>
<xs:element name="indTot" type="xs:string"/>
<xs:element maxOccurs="unbounded" minOccurs="0" name="DI">
<xs:complexType>
<xs:sequence>
<xs:element name="nDI" type="xs:string"/>
<xs:element name="dDI" type="xs:string"/>
<xs:element name="xLocDesemb" type="xs:string"/>
<xs:element name="UFDesemb" type="tUfEmi"/>
<xs:element name="dDesemb" type="xs:string"/>
<xs:element name="cExportador" type="xs:string"/>
<xs:element maxOccurs="unbounded" name="adi">
<xs:complexType>
<xs:sequence>
<xs:element name="nAdicao" type="xs:string"/>
<xs:element name="nSeqAdic" type="xs:string"/>
<xs:element name="cFabricante" type="xs:string"/>
<xs:element minOccurs="0" name="vDescDI" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="xPed" type="xs:string"/>
<xs:element minOccurs="0" name="nItemPed" type="xs:string"/>
<xs:element minOccurs="0" name="veicProd">
<xs:complexType>
<xs:sequence>
<xs:element name="tpOp" type="xs:string"/>
<xs:element name="chassi" type="xs:string"/>
<xs:element name="cCor" type="xs:string"/>
<xs:element name="xCor" type="xs:string"/>
<xs:element name="pot" type="xs:string"/>
<xs:element name="cilin" type="xs:string"/>
<xs:element name="pesoL" type="xs:string"/>
<xs:element name="pesoB" type="xs:string"/>
<xs:element name="nSerie" type="xs:string"/>
<xs:element name="tpComb" type="xs:string"/>
<xs:element name="nMotor" type="xs:string"/>
<xs:element name="CMT" type="xs:string"/>
<xs:element name="dist" type="xs:string"/>
<xs:element name="anoMod" type="xs:string"/>
<xs:element name="anoFab" type="xs:string"/>
<xs:element name="tpPint" type="xs:string"/>
<xs:element name="tpVeic" type="xs:string"/>
<xs:element name="espVeic" type="xs:string"/>
<xs:element name="VIN" type="xs:string"/>
<xs:element name="condVeic" type="xs:string"/>
<xs:element name="cMod" type="xs:string"/>
<xs:element name="cCorDENATRAN" type="xs:string"/>
<xs:element name="lota" type="xs:string"/>
<xs:element name="tpRest" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element maxOccurs="unbounded" minOccurs="0" name="med" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element name="nLote" type="xs:string"/>
<xs:element name="qLote" type="xs:string"/>
<xs:element name="dFab" type="xs:string"/>
<xs:element name="dVal" type="xs:string"/>
<xs:element name="vPMC" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element maxOccurs="unbounded" minOccurs="0" name="arma" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element name="tpArma" type="xs:string"/>
<xs:element name="nSerie" type="xs:string"/>
<xs:element name="nCano" type="xs:string"/>
<xs:element name="descr" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="comb">
<xs:complexType>
<xs:sequence>
<xs:element name="cProdANP" type="xs:string"/>
<xs:element minOccurs="0" name="CODIF" type="xs:string"/>
<xs:element minOccurs="0" name="qTemp" type="xs:string"/>
<xs:element name="UFCons" type="tUf"/>
<xs:element minOccurs="0" name="CIDE">
<xs:complexType>
<xs:sequence>
<xs:element name="qBCProd" type="xs:string"/>
<xs:element name="vAliqProd" type="xs:string"/>
<xs:element name="vCIDE" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="imposto">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="ICMS">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="ICMS00">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element name="modBC" type="xs:string"/>
<xs:element name="vBC" type="xs:string"/>
<xs:element name="pICMS" type="xs:string"/>
<xs:element name="vICMS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS10">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element name="modBC" type="xs:string"/>
<xs:element name="vBC" type="xs:string"/>
<xs:element name="pICMS" type="xs:string"/>
<xs:element name="vICMS" type="xs:string"/>
<xs:element name="modBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pMVAST" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBCST" type="xs:string"/>
<xs:element name="vBCST" type="xs:string"/>
<xs:element name="pICMSST" type="xs:string"/>
<xs:element name="vICMSST" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS20">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element name="modBC" type="xs:string"/>
<xs:element name="pRedBC" type="xs:string"/>
<xs:element name="vBC" type="xs:string"/>
<xs:element name="pICMS" type="xs:string"/>
<xs:element name="vICMS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS30">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element name="modBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pMVAST" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBCST" type="xs:string"/>
<xs:element name="vBCST" type="xs:string"/>
<xs:element name="pICMSST" type="xs:string"/>
<xs:element name="vICMSST" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS40">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element minOccurs="0" name="vICMS" type="xs:string"/>
<xs:element minOccurs="0" name="motDesICMS" type="xs:anyType"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS51">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element minOccurs="0" name="modBC" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBC" type="xs:string"/>
<xs:element minOccurs="0" name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="pICMS" type="xs:string"/>
<xs:element minOccurs="0" name="vICMS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS60">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element name="vBCSTRet" type="xs:string"/>
<xs:element name="vICMSSTRet" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS70">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element name="modBC" type="xs:string"/>
<xs:element name="pRedBC" type="xs:string"/>
<xs:element name="vBC" type="xs:string"/>
<xs:element name="pICMS" type="xs:string"/>
<xs:element name="vICMS" type="xs:string"/>
<xs:element name="modBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pMVAST" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBCST" type="xs:string"/>
<xs:element name="vBCST" type="xs:string"/>
<xs:element name="pICMSST" type="xs:string"/>
<xs:element name="vICMSST" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS90">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element minOccurs="0" name="modBC" type="xs:string"/>
<xs:element minOccurs="0" name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBC" type="xs:string"/>
<xs:element minOccurs="0" name="pICMS" type="xs:string"/>
<xs:element minOccurs="0" name="vICMS" type="xs:string"/>
<xs:element minOccurs="0" name="modBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pMVAST" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBCST" type="xs:string"/>
<xs:element minOccurs="0" name="vBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pICMSST" type="xs:string"/>
<xs:element minOccurs="0" name="vICMSST" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMSPart">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element name="modBC" type="xs:string"/>
<xs:element name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBC" type="xs:string"/>
<xs:element name="pICMS" type="xs:string"/>
<xs:element name="vICMS" type="xs:string"/>
<xs:element name="modBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pMVAST" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBCST" type="xs:string"/>
<xs:element name="vBCST" type="xs:string"/>
<xs:element name="pICMSST" type="xs:string"/>
<xs:element name="vICMSST" type="xs:string"/>
<xs:element name="pBCOp" type="xs:string"/>
<xs:element name="UFST" type="tUf"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMSST">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CST" type="xs:string"/>
<xs:element name="vBCSTRet" type="xs:string"/>
<xs:element name="vICMSSTRet" type="xs:string"/>
<xs:element name="vBCSTDest" type="xs:string"/>
<xs:element name="vICMSSTDest" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS101">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CSOSN" type="xs:string"/>
<xs:element name="pCredSN" type="xs:string"/>
<xs:element name="vCredICMSSN" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMS102">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CSOSN" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMSSN201">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CSOSN" type="xs:string"/>
<xs:element name="modBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pMVAST" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBCST" type="xs:string"/>
<xs:element name="vBCST" type="xs:string"/>
<xs:element name="pICMSST" type="xs:string"/>
<xs:element name="vICMSST" type="xs:string"/>
<xs:element name="pCredSN" type="xs:string"/>
<xs:element name="vCredICMSSN" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMSSN202">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CSOSN" type="xs:string"/>
<xs:element name="modBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pMVAST" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBCST" type="xs:string"/>
<xs:element name="vBCST" type="xs:string"/>
<xs:element name="pICMSST" type="xs:string"/>
<xs:element name="vICMSST" type="xs:string"/>
<xs:element name="pCredSN" type="xs:string"/>
<xs:element name="vCredICMSSN" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMSSN500">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CSOSN" type="xs:string"/>
<xs:element name="vBCSTRet" type="xs:string"/>
<xs:element name="vICMSSTRet" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ICMSSN900">
<xs:complexType>
<xs:sequence>
<xs:element name="orig" type="xs:string"/>
<xs:element name="CSOSN" type="xs:string"/>
<xs:element minOccurs="0" name="modBC" type="xs:string"/>
<xs:element minOccurs="0" name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBC" type="xs:string"/>
<xs:element minOccurs="0" name="pICMS" type="xs:string"/>
<xs:element minOccurs="0" name="vICMS" type="xs:string"/>
<xs:element minOccurs="0" name="modBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pMVAST" type="xs:string"/>
<xs:element minOccurs="0" name="pRedBCST" type="xs:string"/>
<xs:element minOccurs="0" name="vBCST" type="xs:string"/>
<xs:element minOccurs="0" name="pICMSST" type="xs:string"/>
<xs:element minOccurs="0" name="vICMSST" type="xs:string"/>
<xs:element minOccurs="0" name="pCredSN" type="xs:string"/>
<xs:element minOccurs="0" name="vCredICMSSN" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="IPI">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="clEnq" type="xs:string"/>
<xs:element minOccurs="0" name="CNPJProd" type="xs:string"/>
<xs:element minOccurs="0" name="cSelo" type="xs:string"/>
<xs:element minOccurs="0" name="qSelo" type="xs:string"/>
<xs:element name="cEnq" type="xs:string"/>
<xs:element minOccurs="0" name="IPITrib">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
<xs:element minOccurs="0" name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="pIPI" type="xs:string"/>
<xs:element minOccurs="0" name="qUnid" type="xs:string"/>
<xs:element minOccurs="0" name="vUnid" type="xs:string"/>
<xs:element name="vIPI" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="IPINT">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="II">
<xs:complexType>
<xs:sequence>
<xs:element name="vBC" type="xs:string"/>
<xs:element name="vDespAdu" type="xs:string"/>
<xs:element name="vII" type="xs:string"/>
<xs:element name="vIOF" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ISSQN">
<xs:complexType>
<xs:sequence>
<xs:element name="vBC" type="xs:string"/>
<xs:element name="vAliq" type="xs:string"/>
<xs:element name="vISSQN" type="xs:string"/>
<xs:element name="cMunFG" type="xs:string"/>
<xs:element name="cListServ" type="xs:string"/>
<xs:element name="cSitTrib" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="PIS">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="PISAliq">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
<xs:element name="vBC" type="xs:string"/>
<xs:element name="pPIS" type="xs:string"/>
<xs:element name="vPIS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="PISQtde">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
<xs:element name="qBCProd" type="xs:string"/>
<xs:element name="vAliqProd" type="xs:string"/>
<xs:element name="vPIS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="PISNT">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="PISOutr">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
<xs:element minOccurs="0" name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="pPIS" type="xs:string"/>
<xs:element minOccurs="0" name="qBCProd" type="xs:string"/>
<xs:element minOccurs="0" name="vAliqProd" type="xs:string"/>
<xs:element name="vPIS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="PISST">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="pPIS" type="xs:string"/>
<xs:element minOccurs="0" name="qBCProd" type="xs:string"/>
<xs:element minOccurs="0" name="vAliqProd" type="xs:string"/>
<xs:element name="vPIS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="COFINS">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="COFINSAliq">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
<xs:element name="vBC" type="xs:string"/>
<xs:element name="pCOFINS" type="xs:string"/>
<xs:element name="vCOFINS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="COFINSQtde">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
<xs:element name="qBCProd" type="xs:string"/>
<xs:element name="vAliqProd" type="xs:string"/>
<xs:element name="vCOFINS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="COFINSNT">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="COFINSOutr">
<xs:complexType>
<xs:sequence>
<xs:element name="CST" type="xs:string"/>
<xs:element minOccurs="0" name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="pCOFINS" type="xs:string"/>
<xs:element minOccurs="0" name="qBCProd" type="xs:string"/>
<xs:element minOccurs="0" name="vAliqProd" type="xs:string"/>
<xs:element name="vCOFINS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="COFINSST">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="pCOFINS" type="xs:string"/>
<xs:element minOccurs="0" name="qBCProd" type="xs:string"/>
<xs:element minOccurs="0" name="vAliqProd" type="xs:string"/>
<xs:element name="vCOFINS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="infAdProd" type="xs:string"/>
</xs:sequence>
<xs:attribute name="nItem" type="xs:string" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="total">
<xs:complexType>
<xs:sequence>
<xs:element name="ICMSTot">
<xs:complexType>
<xs:sequence>
<xs:element name="vBC" type="xs:string"/>
<xs:element name="vICMS" type="xs:string"/>
<xs:element name="vBCST" type="xs:string"/>
<xs:element name="vST" type="xs:string"/>
<xs:element name="vProd" type="xs:string"/>
<xs:element name="vFrete" type="xs:string"/>
<xs:element name="vSeg" type="xs:string"/>
<xs:element name="vDesc" type="xs:string"/>
<xs:element name="vII" type="xs:string"/>
<xs:element name="vIPI" type="xs:string"/>
<xs:element name="vPIS" type="xs:string"/>
<xs:element name="vCOFINS" type="xs:string"/>
<xs:element name="vOutro" type="xs:string"/>
<xs:element name="vNF" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="ISSQNtot">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="vServ" type="xs:string"/>
<xs:element minOccurs="0" name="vBC" type="xs:string"/>
<xs:element minOccurs="0" name="vISS" type="xs:string"/>
<xs:element minOccurs="0" name="vPIS" type="xs:string"/>
<xs:element minOccurs="0" name="vCOFINS" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="retTrib">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="vRetPIS" type="xs:string"/>
<xs:element minOccurs="0" name="vRetCOFINS" type="xs:string"/>
<xs:element minOccurs="0" name="vRetCSLL" type="xs:string"/>
<xs:element minOccurs="0" name="vBCIRRF" type="xs:string"/>
<xs:element minOccurs="0" name="vIRRF" type="xs:string"/>
<xs:element minOccurs="0" name="vBCRetPrev" type="xs:string"/>
<xs:element minOccurs="0" name="vRetPrev" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="transp">
<xs:complexType>
<xs:sequence>
<xs:element name="modFrete" type="xs:string"/>
<xs:element minOccurs="0" name="transporta">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="CNPJ" type="xs:string"/>
<xs:element minOccurs="0" name="CPF" type="xs:string"/>
<xs:element minOccurs="0" name="xNome" type="xs:string"/>
<xs:element minOccurs="0" name="IE" type="xs:string"/>
<xs:element minOccurs="0" name="xEnder" type="xs:string"/>
<xs:element minOccurs="0" name="xMun" type="xs:string"/>
<xs:element minOccurs="0" name="UF" type="tUf"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="retTransp">
<xs:complexType>
<xs:sequence>
<xs:element name="vServ" type="xs:string"/>
<xs:element name="vBCRet" type="xs:string"/>
<xs:element name="pICMSRet" type="xs:string"/>
<xs:element name="vICMSRet" type="xs:string"/>
<xs:element name="CFOP" type="xs:string"/>
<xs:element name="cMunFG" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="veicTransp" type="TVeiculo"/>
<xs:element maxOccurs="unbounded" minOccurs="0" name="reboque" nillable="true" type="TVeiculo"/>
<xs:element minOccurs="0" name="vagao" type="xs:string"/>
<xs:element minOccurs="0" name="balsa" type="xs:string"/>
<xs:element maxOccurs="unbounded" minOccurs="0" name="vol" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="qVol" type="xs:string"/>
<xs:element minOccurs="0" name="esp" type="xs:string"/>
<xs:element minOccurs="0" name="marca" type="xs:string"/>
<xs:element minOccurs="0" name="nVol" type="xs:string"/>
<xs:element minOccurs="0" name="pesoL" type="xs:string"/>
<xs:element minOccurs="0" name="pesoB" type="xs:string"/>
<xs:element maxOccurs="unbounded" minOccurs="0" name="lacres" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element name="nLacre" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="cobr">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="fat">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="nFat" type="xs:string"/>
<xs:element minOccurs="0" name="vOrig" type="xs:string"/>
<xs:element minOccurs="0" name="vDesc" type="xs:string"/>
<xs:element minOccurs="0" name="vLiq" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element maxOccurs="unbounded" minOccurs="0" name="dup" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="nDup" type="xs:string"/>
<xs:element minOccurs="0" name="dVenc" type="xs:string"/>
<xs:element minOccurs="0" name="vDup" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="infAdic">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="infAdFisco" type="xs:string"/>
<xs:element minOccurs="0" name="infCpl" type="xs:string"/>
<xs:element maxOccurs="unbounded" minOccurs="0" name="obsCont" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element name="xTexto" type="xs:string"/>
</xs:sequence>
<xs:attribute name="xCampo" type="xs:string" use="required"/>
</xs:complexType>
</xs:element>
<xs:element maxOccurs="unbounded" minOccurs="0" name="obsFisco" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element name="xTexto" type="xs:string"/>
</xs:sequence>
<xs:attribute name="xCampo" type="xs:string" use="required"/>
</xs:complexType>
</xs:element>
<xs:element maxOccurs="unbounded" minOccurs="0" name="procRef" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element name="nProc" type="xs:string"/>
<xs:element name="indProc" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="exporta">
<xs:complexType>
<xs:sequence>
<xs:element name="UFEmbarq" type="tUf"/>
<xs:element name="xLocEmbarq" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="compra">
<xs:complexType>
<xs:sequence>
<xs:element minOccurs="0" name="xNEmp" type="xs:string"/>
<xs:element minOccurs="0" name="xPed" type="xs:string"/>
<xs:element minOccurs="0" name="xCont" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element minOccurs="0" name="cana">
<xs:complexType>
<xs:sequence>
<xs:element name="safra" type="xs:string"/>
<xs:element name="ref" type="xs:string"/>
<xs:element maxOccurs="unbounded" name="forDia">
<xs:complexType>
<xs:sequence>
<xs:element name="qtde" type="xs:string"/>
</xs:sequence>
<xs:attribute name="dia" type="xs:string" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="qTotMes" type="xs:string"/>
<xs:element name="qTotAnt" type="xs:string"/>
<xs:element name="qTotGer" type="xs:string"/>
<xs:element maxOccurs="unbounded" minOccurs="0" name="deduc" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element name="xDed" type="xs:string"/>
<xs:element name="aliq" type="xs:string"/>
<xs:element name="vDed" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:element name="vFor" type="xs:string"/>
<xs:element name="vTodDed" type="xs:string"/>
<xs:element name="vLiqFor" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
<xs:attribute name="Id" type="xs:ID" use="required"/>
</xs:complexType>
</xs:element>
<xs:element name="Signature" type="SignatureType"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TEnviNFe">
<xs:sequence>
<xs:element name="idLote" type="xs:string"/>
<xs:element maxOccurs="unbounded" name="NFe" type="TNFe"/>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="TNfeProc">
<xs:sequence>
<xs:element name="NFe" type="TNFe"/>
<xs:element name="protNFe" type="TProtNFe"/>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
</xs:complexType>
<xs:simpleType name="tUf">
<xs:restriction base="xs:string">
<xs:enumeration value="AC"/>
<xs:enumeration value="AL"/>
<xs:enumeration value="AM"/>
<xs:enumeration value="AP"/>
<xs:enumeration value="BA"/>
<xs:enumeration value="CE"/>
<xs:enumeration value="DF"/>
<xs:enumeration value="ES"/>
<xs:enumeration value="GO"/>
<xs:enumeration value="MA"/>
<xs:enumeration value="MG"/>
<xs:enumeration value="MS"/>
<xs:enumeration value="MT"/>
<xs:enumeration value="PA"/>
<xs:enumeration value="PB"/>
<xs:enumeration value="PE"/>
<xs:enumeration value="PI"/>
<xs:enumeration value="PR"/>
<xs:enumeration value="RJ"/>
<xs:enumeration value="RN"/>
<xs:enumeration value="RO"/>
<xs:enumeration value="RR"/>
<xs:enumeration value="RS"/>
<xs:enumeration value="SC"/>
<xs:enumeration value="SE"/>
<xs:enumeration value="SP"/>
<xs:enumeration value="TO"/>
<xs:enumeration value="EX"/>
</xs:restriction>
</xs:simpleType>
<xs:simpleType name="tUfEmi">
<xs:restriction base="xs:string">
<xs:enumeration value="AC"/>
<xs:enumeration value="AL"/>
<xs:enumeration value="AM"/>
<xs:enumeration value="AP"/>
<xs:enumeration value="BA"/>
<xs:enumeration value="CE"/>
<xs:enumeration value="DF"/>
<xs:enumeration value="ES"/>
<xs:enumeration value="GO"/>
<xs:enumeration value="MA"/>
<xs:enumeration value="MG"/>
<xs:enumeration value="MS"/>
<xs:enumeration value="MT"/>
<xs:enumeration value="PA"/>
<xs:enumeration value="PB"/>
<xs:enumeration value="PE"/>
<xs:enumeration value="PI"/>
<xs:enumeration value="PR"/>
<xs:enumeration value="RJ"/>
<xs:enumeration value="RN"/>
<xs:enumeration value="RO"/>
<xs:enumeration value="RR"/>
<xs:enumeration value="RS"/>
<xs:enumeration value="SC"/>
<xs:enumeration value="SE"/>
<xs:enumeration value="SP"/>
<xs:enumeration value="TO"/>
</xs:restriction>
</xs:simpleType>
</xs:schema>
  </wsdl:types>
  <wsdl:message name="nfeRetRecepcao2">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="nfeRetRecepcao2Response">
    <wsdl:part element="tns:nfeRetRecepcao2Result" name="nfeRetRecepcao2Result">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="NfeRetRecepcao2Soap">
    <wsdl:operation name="nfeRetRecepcao2" parameterOrder="nfeDadosMsg nfeCabecMsg">
      <wsdl:input message="tns:nfeRetRecepcao2" name="nfeRetRecepcao2">
    </wsdl:input>
      <wsdl:output message="tns:nfeRetRecepcao2Response" name="nfeRetRecepcao2Response">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="NfeRetRecepcao2SoapBinding" type="tns:NfeRetRecepcao2Soap">
    <soap12:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="nfeRetRecepcao2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/NfeRetRecepcao2/nfeRetRecepcao2" style="document"/>
      <wsdl:input name="nfeRetRecepcao2">
        <soap12:header message="tns:nfeRetRecepcao2" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeDadosMsg" use="literal"/>
      </wsdl:input>
      <wsdl:output name="nfeRetRecepcao2Response">
        <soap12:header message="tns:nfeRetRecepcao2Response" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeRetRecepcao2Result" use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="NfeRetRecepcao2">
    <wsdl:port binding="tns:NfeRetRecepcao2SoapBinding" name="NfeRetRecepcao2Soap12">
      <soap12:address location="https://nfe.fazenda.mg.gov.br/nfe2/services/NfeRetRecepcao2"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>