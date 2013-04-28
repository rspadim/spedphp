<?xml version="1.0" encoding="UTF-8"?>
<wsdl:definitions name="CadConsultaCadastro2" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:ns1="http://schemas.xmlsoap.org/soap/http" xmlns:soap12="http://schemas.xmlsoap.org/wsdl/soap12/" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <wsdl:types>
<xs:schema attributeFormDefault="unqualified" elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:tns="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:import namespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2"/>
<xs:element name="consultaCadastro2Result" type="tns:consultaCadastro2Result"/>
<xs:element name="nfeCabecMsg" type="tns:nfeCabecMsg"/>
<xs:element name="nfeDadosMsg">
<xs:complexType mixed="true">
<xs:sequence>
<xs:any maxOccurs="unbounded" minOccurs="0" namespace="##other" processContents="lax"/>
</xs:sequence>
</xs:complexType>
</xs:element>
<xs:complexType mixed="true" name="consultaCadastro2Result">
<xs:sequence>
<xs:element maxOccurs="unbounded" minOccurs="0" ref="retConsCad"/>
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
<xs:schema attributeFormDefault="unqualified" elementFormDefault="qualified" targetNamespace="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2" xmlns:xs="http://www.w3.org/2001/XMLSchema">
<xs:element name="retConsCad" type="TRetConsCad"/>
<xs:complexType name="TRetConsCad">
<xs:sequence>
<xs:element form="unqualified" name="infCons">
<xs:complexType>
<xs:sequence>
<xs:element form="unqualified" name="verAplic" type="xs:string"/>
<xs:element form="unqualified" name="cStat" type="xs:string"/>
<xs:element form="unqualified" name="xMotivo" type="xs:string"/>
<xs:element form="unqualified" name="UF" type="tUfCons"/>
<xs:element form="unqualified" minOccurs="0" name="IE" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="CNPJ" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="CPF" type="xs:string"/>
<xs:element form="unqualified" name="dhCons" type="xs:anySimpleType"/>
<xs:element form="unqualified" name="cUF" type="xs:string"/>
<xs:element form="unqualified" maxOccurs="unbounded" minOccurs="0" name="infCad" nillable="true">
<xs:complexType>
<xs:sequence>
<xs:element form="unqualified" name="IE" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="CNPJ" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="CPF" type="xs:string"/>
<xs:element form="unqualified" name="UF" type="tUf"/>
<xs:element form="unqualified" name="cSit" type="xs:string"/>
<xs:element form="unqualified" name="indCredNFe" type="xs:string"/>
<xs:element form="unqualified" name="indCredCTe" type="xs:string"/>
<xs:element form="unqualified" name="xNome" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="xFant" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="xRegApur" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="CNAE" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="dIniAtiv" type="xs:date"/>
<xs:element form="unqualified" minOccurs="0" name="dUltSit" type="xs:date"/>
<xs:element form="unqualified" minOccurs="0" name="dBaixa" type="xs:date"/>
<xs:element form="unqualified" minOccurs="0" name="IEUnica" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="IEAtual" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="ender" type="TEndereco"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
<xs:attribute name="xmlns" type="xs:string" use="required"/>
</xs:complexType>
<xs:complexType name="TEndereco">
<xs:sequence>
<xs:element form="unqualified" minOccurs="0" name="xLgr" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="nro" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="xCpl" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="xBairro" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="cMun" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="xMun" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="CEP" type="xs:string"/>
</xs:sequence>
</xs:complexType>
<xs:complexType name="TConsCad">
<xs:sequence>
<xs:element form="unqualified" name="infCons">
<xs:complexType>
<xs:sequence>
<xs:element form="unqualified" name="xServ" type="xs:string"/>
<xs:element form="unqualified" name="UF" type="tUfCons"/>
<xs:element form="unqualified" minOccurs="0" name="IE" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="CNPJ" type="xs:string"/>
<xs:element form="unqualified" minOccurs="0" name="CPF" type="xs:string"/>
</xs:sequence>
</xs:complexType>
</xs:element>
</xs:sequence>
<xs:attribute name="versao" type="xs:string" use="required"/>
</xs:complexType>
<xs:simpleType name="tUfCons">
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
<xs:enumeration value="SU"/>
</xs:restriction>
</xs:simpleType>
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
</xs:schema>
  </wsdl:types>
  <wsdl:message name="consultaCadastro2">
    <wsdl:part element="tns:nfeDadosMsg" name="nfeDadosMsg">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:message name="consultaCadastro2Response">
    <wsdl:part element="tns:consultaCadastro2Result" name="consultaCadastro2Result">
    </wsdl:part>
    <wsdl:part element="tns:nfeCabecMsg" name="nfeCabecMsg">
    </wsdl:part>
  </wsdl:message>
  <wsdl:portType name="CadConsultaCadastro2Soap12">
    <wsdl:operation name="consultaCadastro2" parameterOrder="nfeDadosMsg nfeCabecMsg">
      <wsdl:input message="tns:consultaCadastro2" name="consultaCadastro2">
    </wsdl:input>
      <wsdl:output message="tns:consultaCadastro2Response" name="consultaCadastro2Response">
    </wsdl:output>
    </wsdl:operation>
  </wsdl:portType>
  <wsdl:binding name="CadConsultaCadastro2SoapBinding" type="tns:CadConsultaCadastro2Soap12">
    <soap12:binding style="document" transport="http://schemas.xmlsoap.org/soap/http"/>
    <wsdl:operation name="consultaCadastro2">
      <soap12:operation soapAction="http://www.portalfiscal.inf.br/nfe/wsdl/CadConsultaCadastro2/consultaCadastro2" style="document"/>
      <wsdl:input name="consultaCadastro2">
        <soap12:header message="tns:consultaCadastro2" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="nfeDadosMsg" use="literal"/>
      </wsdl:input>
      <wsdl:output name="consultaCadastro2Response">
        <soap12:header message="tns:consultaCadastro2Response" part="nfeCabecMsg" use="literal">
        </soap12:header>
        <soap12:body parts="consultaCadastro2Result" use="literal"/>
      </wsdl:output>
    </wsdl:operation>
  </wsdl:binding>
  <wsdl:service name="CadConsultaCadastro2">
    <wsdl:port binding="tns:CadConsultaCadastro2SoapBinding" name="CadConsultaCadastro2Soap12">
      <soap12:address location="https://hnfe.fazenda.mg.gov.br/nfe2/services/cadconsultacadastro2"/>
    </wsdl:port>
  </wsdl:service>
</wsdl:definitions>